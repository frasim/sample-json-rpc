<?php


class DbConnectivityTest extends TestCase
{
    public function testConnection() {
        /** @var \Illuminate\Database\Connection $db */
        $db = $this->app->make("db");
        $row = $db->selectOne("SELECT 1 AS test");
        $this->assertEquals(1, $row->test);
    }
}
