<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Driver;
use App\Entity\Vehicle;
use App\Repository\DriverRepository;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DriverControllerTest extends ApiTestCase
{
    use RecreateDatabaseTrait;

    protected function setUp(): void
    {
        $this->client = $this->createClient();
        $this->login();
    }

    public function testIndex()
    {
        $this->create([
            [
                "name" => "Nome motorista 1",
                "document" => "357.472.110-21",
                "vehicleName" => "onix",
                "vehicleColor" => "prata",
                "vehiclePlate" => "PEP7D82"
            ], [
                "name" => "Nome motorista 2",
                "document" => "778.064.880-06",
                "vehicleName" => "onix",
                "vehicleColor" => "prata",
                "vehiclePlate" => "PEP7D83"
            ]
        ]);
        $response = $this->request('GET', '/driver');

        $this->assertCount(4, $response->toArray());
        $this->assertResponseIsSuccessful();
        $this->assertIsObject($response);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testShow()
    {
        $this->request('POST', '/driver', [
            'json' => [
                "name" => "Nome motorista 2",
                "document" => "778.064.880-06",
                "vehicleName" => "onix",
                "vehicleColor" => "prata",
                "vehiclePlate" => "PEP7D83"
            ]
        ]);
        $driverCreate = $this->getDriverRepository()->findOneBy(['document' => '77806488006']);
        $this->request('GET', "/driver/{$driverCreate->getId()}");
        $this->assertResponseIsSuccessful();
    }


    public function testUpdate()
    {
        $this->create([
            [
                "name" => "Nome motorista 1",
                "document" => "357.472.110-21",
                "vehicleName" => "onix",
                "vehicleColor" => "prata",
                "vehiclePlate" => "PEP7D82"
            ]
        ]);

        $driverCreate = $this->getDriverRepository()->findOneBy(['document' => '35747211021']);
        $this->request('PUT', "/driver/{$driverCreate->getId()}", [
            'json' => [
                "name" => "Nome atualizado",
                "document" => "35747211021",
                "vehicleName" => "onix",
                "vehicleColor" => "prata",
                "vehiclePlate" => "PEP7D84"
            ]
        ]);

        $driverUpdate = $this->getDriverRepository()->findOneBy(['document' => '35747211021']);

        $this->assertInstanceOf(Vehicle::class, $driverCreate->getVehicle());
        $this->assertInstanceOf(Driver::class, $driverCreate);
        $this->assertNotEquals($driverUpdate->getName(), $driverCreate->getName());
        $this->assertNotEquals($driverUpdate->getVehicle()->getPlate(), $driverCreate->getVehicle()->getPlate());
        $this->assertResponseIsSuccessful();
    }

    public function testCreate()
    {
        $this->create([
            [
                "name" => "Nome motorista 1",
                "document" => "357.472.110-21",
                "vehicleName" => "onix",
                "vehicleColor" => "prata",
                "vehiclePlate" => "PEP7D82"
            ], [
                "name" => "Nome motorista 2",
                "document" => "778.064.880-06",
                "vehicleName" => "onix",
                "vehicleColor" => "prata",
                "vehiclePlate" => "PEP7D83"
            ]
        ]);
        $drivers = $this->getDriverRepository()->findAll();

        $this->assertCount(2, $drivers);
        $this->assertResponseIsSuccessful();
    }

    public function testDelete()
    {
        $this->request('POST', '/driver', [
            'json' => [
                "name" => "Nome motorista",
                "document" => "002.163.950-70",
                "vehicleName" => "onix",
                "vehicleColor" => "prata",
                "vehiclePlate" => "PEP7D81"
            ]
        ]);

        $driverCreate = $this->getDriverRepository()->findOneBy(['document' => '00216395070']);

        $this->request('DELETE', "/driver/{$driverCreate->getId()}");
        $driverUpdate = $this->getDriverRepository()->findOneBy(['document' => '00216395070']);
        $this->assertResponseIsSuccessful();
        $this->assertEmpty($driverUpdate);
    }

    private function login()
    {
        $response = $this->client->request('POST', '/api/auth/login', [
            'json' => [
                "username" => "admin@hotmail.com",
                "password" => "123456",
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->token = $response->toArray()['token'];
    }

    private function create(array $data)
    {
        foreach ($data as $item) {
            $this->request('POST', '/driver', [
                'json' => $item
            ]);
            $this->assertResponseIsSuccessful();
        }

    }

    /**
     * @return DriverRepository
     */
    private function getDriverRepository(): object
    {
        return $this->getContainer()->get(DriverRepository::class);
    }

    protected function getToken(): string
    {
        return  "Bearer {$this->token}";
    }

    private function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->client->request($method, "/api{$url}", array_merge([
            'headers' => ['Authorization' => $this->getToken()]
        ], $options));
    }
}
