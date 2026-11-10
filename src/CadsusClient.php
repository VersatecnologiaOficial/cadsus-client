<?php

namespace Versatecnologia\Cadsus;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Versatecnologia\Cadsus\Authentication\AuthenticationClient;
use Versatecnologia\Cadsus\Authentication\TokenProvider;
use Versatecnologia\Cadsus\Cache\InMemoryTokenStore;
use Versatecnologia\Cadsus\Cache\LocalLock;
use Versatecnologia\Cadsus\Contracts\CadsusClientInterface;
use Versatecnologia\Cadsus\Contracts\CredentialedCadsusClientInterface;
use Versatecnologia\Cadsus\Contracts\LockInterface;
use Versatecnologia\Cadsus\Contracts\TokenStoreInterface;
use Versatecnologia\Cadsus\DTO\CadsusCredentials;
use Versatecnologia\Cadsus\DTO\CadsusSearchRequest;
use Versatecnologia\Cadsus\DTO\CadsusSearchResult;
use Versatecnologia\Cadsus\Exceptions\CadsusException;
use Versatecnologia\Cadsus\HL7\PdqRequestBuilder;
use Versatecnologia\Cadsus\HL7\PdqResponseParser;
use Versatecnologia\Cadsus\Soap\PdqSoapTransport;
use Versatecnologia\Cadsus\Soap\SoapClientFactory;

final class CadsusClient implements CadsusClientInterface, CredentialedCadsusClientInterface
{
    public function __construct(private readonly TokenProvider $tokens, private readonly PdqRequestBuilder $builder, private readonly PdqSoapTransport $transport, private readonly PdqResponseParser $parser, private readonly ?CadsusCredentials $credentials = null) {}

    public static function create(?ClientInterface $http = null, ?TokenStoreInterface $store = null, ?LockInterface $lock = null): self
    {
        $tokens = new TokenProvider(new AuthenticationClient($http ?? new Client()), $store ?? new InMemoryTokenStore(), $lock ?? new LocalLock());
        return new self($tokens, new PdqRequestBuilder(), new PdqSoapTransport(new SoapClientFactory()), new PdqResponseParser());
    }

    public function forCredentials(CadsusCredentials $credentials): CredentialedCadsusClientInterface
    {
        return new self($this->tokens, $this->builder, $this->transport, $this->parser, $credentials);
    }

    public function search(CadsusSearchRequest $request): CadsusSearchResult
    {
        if ($this->credentials === null) throw new CadsusException('As credenciais CADSUS não foram informadas.', 'credentials');
        $id = bin2hex(random_bytes(16));
        $payload = $this->builder->build($request, $id);
        try {
            return $this->parser->parse($this->transport->query($payload, $this->credentials->environment, $this->tokens->token($this->credentials)), $id);
        } catch (CadsusException $e) {
            if ($e->category !== 'token_rejected') throw $e;
            $this->tokens->invalidate($this->credentials);
            return $this->parser->parse($this->transport->query($payload, $this->credentials->environment, $this->tokens->token($this->credentials, true)), $id);
        }
    }
}
