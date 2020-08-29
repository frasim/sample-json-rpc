<?php


class SuccessTest extends TestCase
{
    const JSON_REQUEST_EXAMPLE_1 = '{"jsonrpc":"2.0","id":"1","method":"SearchNearestPharmacy","params":{"currentLocation":{"latitude":"41.10938993","longitude":"15.0321010"},"range":"5000","limit":"2"}}';
    const JSON_REQUEST_EXAMPLE_2 = '{"jsonrpc":"2.0","id":"1","method":"SearchNearestPharmacy","params":{"currentLocation":{"latitude":"40.854953","longitude":"14.251711"},"range":"500","limit":"10"}}';
    const JSON_REQUEST_EXAMPLE_3 = '{"jsonrpc":"2.0","id":"1","method":"SearchNearestPharmacy","params":{"currentLocation":{"latitude":"40.854953","longitude":"14.251711"},"range":"0","limit":"10"}}';

    const JSON_RESPONSE_EXAMPLE_1 = '{"id":"1","jsonrpc":"2.0","result":{"pharmacies":[{"name":"Belmonte Di Dott.sse Belmonte S. Ed E. Snc","latitude":"41.11","longitude":"15.03","distance":"39.99961698"}]}}';
    const JSON_RESPONSE_EXAMPLE_2 = '{"id":"1","jsonrpc":"2.0","result":{"pharmacies":[{"name":"CAVOUR SAS DI DONNA FRANCESCO & C.","latitude":"40.85","longitude":"14.25","distance":"242.02487815"},{"name":"DEL SOLE DI LETIZIA LIMONE E PASQUALE RUSSO SNC","latitude":"40.85","longitude":"14.25","distance":"242.02487815"},{"name":"RUSSO MAURIZIO","latitude":"40.85","longitude":"14.25","distance":"242.02487815"},{"name":"MARASCO TIZIANA","latitude":"40.85","longitude":"14.25","distance":"445.67781879"},{"name":"FARMACIE FERLAINO S.N.C. DEI DOTTORI GIOVANNI E CHRISTIAN FERLAINO","latitude":"40.86","longitude":"14.26","distance":"444.80036815"},{"name":"FERLAINO GIOVANNI","latitude":"40.86","longitude":"14.26","distance":"444.80036815"},{"name":"MAGGIORE TOMMASO","latitude":"40.85","longitude":"14.25","distance":"495.77418459"},{"name":"NAZIONALE DI DE SIO CESARI GIOVANNI E C. SNC","latitude":"40.85","longitude":"14.25","distance":"495.77418459"},{"name":"AMODIO SIMONA","latitude":"40.85","longitude":"14.25","distance":"495.77418459"},{"name":"SALES DEL DOTT. SOLLO VINCENZO","latitude":"40.85","longitude":"14.25","distance":"495.77418459"}]}}';
    const JSON_RESPONSE_EXAMPLE_3 = '{"id":"1","jsonrpc":"2.0","result":{"pharmacies":[]}}';

    public function testExample1()
    {
        $this->json('POST', '/', json_decode(static::JSON_REQUEST_EXAMPLE_1, true))
            ->seeJson(json_decode(static::JSON_RESPONSE_EXAMPLE_1, true));
    }

    public function testExample2()
    {
        $this->json('POST', '/', json_decode(static::JSON_REQUEST_EXAMPLE_2, true))
            ->seeJson(json_decode(static::JSON_RESPONSE_EXAMPLE_2, true));
    }

    public function testExample3()
    {
        $this->json('POST', '/', json_decode(static::JSON_REQUEST_EXAMPLE_3, true))
            ->seeJson(json_decode(static::JSON_RESPONSE_EXAMPLE_3, true));
    }
}
