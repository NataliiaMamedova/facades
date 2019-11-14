<?php
declare(strict_types=1);

namespace DpDocument\Adapters\Tests\Buildings;

use DpDocument\Facades\Buildings\BuildingsFacade;
use DpDocument\Facades\Buildings\DTO\Agreement;
use DpDocument\Facades\Buildings\DTO\Attachment;
use DpDocument\Facades\Buildings\DTO\Building;
use DpDocument\Facades\Buildings\DTO\Competitor;
use DpDocument\Facades\Buildings\DTO\Complex;
use DpDocument\Facades\Buildings\DTO\House;
use DpDocument\Facades\Buildings\DTO\Housing;
use DpDocument\Facades\Buildings\DTO\MultiApartment;
use DpDocument\Facades\Buildings\DTO\Section;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * Class BuildingsFacadeTest
 *
 * @package DpDocument\Adapters\Tests\Buildings
 * DpDocument | Research & Development
 */
class BuildingsFacadeTest extends TestCase
{
    /**
     * @var string
     */
    private static $baseApiUrl = 'http://some.host/api';

    public function testGetBuildings()
    {
        $token = 'some-token';
        $data = [
            [
                'floors' => 2,
                'type' => 4,
                'id' => 'f6098949-b711-49c6-a16a-3105861a7b32',
                'name' => 'Test Housing',
                'google_place_id' => 'test',
                'photos' => [
                    [
                        'id' => 29,
                        'link' => 'daweawdaw',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ],
                    [
                        'id' => 30,
                        'link' => 'second',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ]
                ],
                'housing' => [
                    'id'=> 11,
                    'name' => 'Test',
                    'address' => 'sdiafhiuhgsdihgioushdgi',
                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'created_at' => '2018-02-20T12:25:01+02:00',
                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'updated_at' => '2018-02-20T12:25:01+02:00'
                ],
                'agreements' => [
                    [
                        'id' => 16,
                        'files' => [
                            [
                                'id' => 26,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ],
                            [
                                'id' => 27,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'sign_date' => '2018-01-29T17:02:05+02:00',
                        'expires' => '2018-01-29T17:02:05+02:00',
                        'auto_extend' => false,
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:16+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:16+02:00'
                    ]
                ],
                'competitors' => [
                    [
                        'id' => 14,
                        'name' => 'Test Competitor',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:06:13+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:06:13+02:00'
                    ]
                ],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T14:10:11+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T14:10:11+02:00',
                'object_type' => 'BH'
            ],
            [
                'floors' => 30,
                'type' => 2,
                'rooms' => 3050,
                'sections' => [
                    [
                        'floors' => null,
                        'rooms' => null,
                        'section_number' => null,
                        'type' => 3,
                        'id' => 'b65e6647-1749-4ab8-8c0e-21909533f23c',
                        'name' => '321dwadaw',
                        'google_place_id' => null,
                        'photos' => [
                            [
                                'id' => 53,
                                'link' => '321dadwa',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:18:48+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:18:48+02:00'
                            ],
                            [
                                'id' => 54,
                                'link' => '321dadwa2',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:18:48+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:18:48+02:00'
                            ]
                        ],
                        'housing' => [
                            'id'=> 11,
                            'name' => 'Test',
                            'address' => 'sdiafhiuhgsdihgioushdgi',
                            'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'created_at' => '2018-02-20T12:25:01+02:00',
                            'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'updated_at' => '2018-02-20T12:25:01+02:00'
                        ],
                        'agreements' => [
                            [
                                'id' => 16,
                                'files' => [
                                    [
                                        'id' => 26,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ],
                                    [
                                        'id' => 27,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ]
                                ],
                                'sign_date' => '2018-01-29T17:02:05+02:00',
                                'expires' => '2018-01-29T17:02:05+02:00',
                                'auto_extend' => false,
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'competitors' => [
                            [
                                'id' => 14,
                                'name' => 'Test Competitor',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:06:13+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:06:13+02:00'
                            ]
                        ],
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:18:05+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:18:48+02:00',
                        'object_type' => null
                    ]
                ],
                'id' => '32c732e4-6fb0-476c-b4f6-7c9b895fea7a',
                'name' => 'Test 2',
                'google_place_id' => 'new testing',
                'photos' => [
                    [
                        'id' => 29,
                        'link' => 'daweawdaw',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ],
                    [
                        'id' => 30,
                        'link' => 'second',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ]
                ],
                'housing' => [
                    'id'=> 11,
                    'name' => 'Test',
                    'address' => 'sdiafhiuhgsdihgioushdgi',
                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'created_at' => '2018-02-20T12:25:01+02:00',
                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'updated_at' => '2018-02-20T12:25:01+02:00'
                ],
                'agreements' => [
                    [
                        'id' => 16,
                        'files' => [
                            [
                                'id' => 26,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ],
                            [
                                'id' => 27,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'sign_date' => '2018-01-29T17:02:05+02:00',
                        'expires' => '2018-01-29T17:02:05+02:00',
                        'auto_extend' => false,
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:16+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:16+02:00'
                    ]
                ],
                'competitors' => [
                    [
                        'id' => 14,
                        'name' => 'Test Competitor',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:06:13+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:06:13+02:00'
                    ]
                ],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T12:40:41+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T12:40:41+02:00',
                'object_type' => 'BGH'
            ],
            [
                'multi_apartment' => [
                    [
                        'floors' => null,
                        'rooms' => null,
                        'sections' => [
                            [
                                'floors' => null,
                                'rooms' => null,
                                'section_number' => null,
                                'type' => 3,
                                'id' => 'b65e6647-1749-4ab8-8c0e-21909533f23c',
                                'name' => '321dwadaw',
                                'google_place_id' => null,
                                'photos' => [
                                    [
                                        'id' => 53,
                                        'link' => '321dadwa',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:18:48+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:18:48+02:00'
                                    ],
                                    [
                                        'id' => 54,
                                        'link' => '321dadwa2',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:18:48+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:18:48+02:00'
                                    ]
                                ],
                                'housing' => [
                                    'id'=> 11,
                                    'name' => 'Test',
                                    'address' => 'sdiafhiuhgsdihgioushdgi',
                                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                                    'created_at' => '2018-02-20T12:25:01+02:00',
                                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                                    'updated_at' => '2018-02-20T12:25:01+02:00'
                                ],
                                'agreements' => [
                                    [
                                        'id' => 16,
                                        'files' => [
                                            [
                                                'id' => 26,
                                                'link' => 'dawdaw',
                                                'created_by' => 'admin',
                                                'created_at' => '2018-02-20T12:34:16+02:00',
                                                'edited_by' => 'admin',
                                                'updated_at' => '2018-02-20T12:34:16+02:00'
                                            ],
                                            [
                                                'id' => 27,
                                                'link' => 'dawdaw',
                                                'created_by' => 'admin',
                                                'created_at' => '2018-02-20T12:34:16+02:00',
                                                'edited_by' => 'admin',
                                                'updated_at' => '2018-02-20T12:34:16+02:00'
                                            ]
                                        ],
                                        'sign_date' => '2018-01-29T17:02:05+02:00',
                                        'expires' => '2018-01-29T17:02:05+02:00',
                                        'auto_extend' => false,
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ]
                                ],
                                'competitors' => [
                                    [
                                        'id' => 14,
                                        'name' => 'Test Competitor',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:06:13+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:06:13+02:00'
                                    ]
                                ],
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:18:05+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:18:48+02:00',
                                'object_type' => null
                            ]
                        ],
                        'type' => 2,
                        'id' => '49c9f8be-6189-4b73-97ec-365c1f4e25dd',
                        'name' => '321dwadaw',
                        'google_place_id' => null,
                        'photos' => [
                            [
                                'id' => 51,
                                'link' => '321dadwa',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:17:46+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:17:46+02:00'
                            ],
                            [
                                'id' => 52,
                                'link' => '321dadwa2',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:17:46+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:17:46+02:00'
                            ]
                        ],
                        'housing' => [
                            'id'=> 11,
                            'name' => 'Test',
                            'address' => 'sdiafhiuhgsdihgioushdgi',
                            'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'created_at' => '2018-02-20T12:25:01+02:00',
                            'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'updated_at' => '2018-02-20T12:25:01+02:00'
                        ],
                        'agreements' => [
                            [
                                'id' => 20,
                                'files' => [
                                    [
                                        'id' => 48,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:16:28+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:16:28+02:00'
                                    ],
                                    [
                                        'id' => 49,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:16:28+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:16:28+02:00'
                                    ]
                                ],
                                'sign_date' => '2018-01-29T17:02:05+02:00',
                                'expires' => '2018-01-29T17:02:05+02:00',
                                'auto_extend' => false,
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:16:28+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:16:28+02:00'
                            ]
                        ],
                        'competitors' => [
                            [
                                'id' => 14,
                                'name' => 'Test Competitor',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:06:13+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:06:13+02:00'
                            ]
                        ],
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:15:55+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-23T15:25:23+02:00',
                        'object_type' => null
                    ]
                ],
                'type' => 1,
                'id' => 'f9808785-9a36-4570-9a31-10d330b3bd05',
                'name' => '3123dadaw',
                'google_place_id' => null,
                'photos' => [
                    [
                        'id' => 29,
                        'link' => 'daweawdaw',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ],
                    [
                        'id' => 30,
                        'link' => 'second',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ]
                ],
                'housing' => [
                    'id'=> 11,
                    'name' => 'Test',
                    'address' => 'sdiafhiuhgsdihgioushdgi',
                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'created_at' => '2018-02-20T12:25:01+02:00',
                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'updated_at' => '2018-02-20T12:25:01+02:00'
                ],
                'agreements' => [
                    [
                        'id' => 16,
                        'files' => [
                            [
                                'id' => 26,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ],
                            [
                                'id' => 27,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'sign_date' => '2018-01-29T17:02:05+02:00',
                        'expires' => '2018-01-29T17:02:05+02:00',
                        'auto_extend' => false,
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:16+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:16+02:00'
                    ]
                ],
                'competitors' => [
                    [
                        'id' => 14,
                        'name' => 'Test Competitor',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:06:13+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:06:13+02:00'
                    ]
                ],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T14:47:27+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T14:47:27+02:00',
                'object_type' => null
            ]
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $buildings = $buildingsFacade->getBuildings($token);
        $this->assertContainsOnlyInstancesOf(Building::class, $buildings);

        $this->assertHouse($data[0], $buildings[0]);

        $this->assertMultiApartment($data[1], $buildings[1]);

        $complex = $buildings[2];
        $this->assertComplex($data[2], $complex);
        $this->assertMultiApartment($data[2]['multi_apartment'][0], $complex->multiApartments[0]);
    }

    public function testGetCompetitors()
    {
        $token = 'some-token';
        $data = [
            [
                'id' => 1,
                'name' => 'Test',
                'created_by' => 'admin',
                'created_at' => '2018-02-20T12:25:01+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T12:25:01+02:00'
            ],
            [
                'id' => 2,
                'name' => 'Test',
                'created_by' => 'admin',
                'created_at' => '2018-02-20T12:25:01+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T12:25:01+02:00'
            ]
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $competitors = $buildingsFacade->getCompetitors($token);
        $this->assertContainsOnlyInstancesOf(Competitor::class, $competitors);
    }

    public function testGetCompetitor()
    {
        $token = 'some-token';
        $data = [
            'id' => 1,
            'name' => 'Test',
            'created_by' => 'admin',
            'created_at' => '2018-02-20T12:25:01+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T12:25:01+02:00'
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $competitor = $buildingsFacade->getCompetitor($token, 1);
        $this->assertInstanceOf(Competitor::class, $competitor);
    }

    public function testNewCompetitor()
    {
        $token = 'some-token';
        $data = [
            'id' => 25,
            'name' => 'Test',
            'created_by' => 'admin',
            'created_at' => '2018-02-20T12:25:01+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T12:25:01+02:00'
        ];

        $client = $this->createClient($data, 200);

        $buildingsFacade   = new BuildingsFacade($client, self::$baseApiUrl);
        $newCompetitor = $buildingsFacade->newCompetitor($token, $data['name']);

        $this->assertInstanceOf(Competitor::class, $newCompetitor);
        $this->assertEquals($data['name'], $newCompetitor->name);
        $this->assertEquals(new \DateTimeImmutable($data['created_at']), $newCompetitor->createdAt);
        $this->assertEquals($data['edited_by'], $newCompetitor->editedBy);
        $this->assertEquals($data['created_by'], $newCompetitor->createdBy);
        $this->assertEquals(new \DateTimeImmutable($data['updated_at']), $newCompetitor->updatedAt);
    }

    public function testUpdateCompetitor()
    {
        $token = 'test-token';
        $data  = [
            'id' => 25,
            'name' => 'Test',
            'created_by' => 'admin',
            'created_at' => '2018-02-20T12:25:01+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T12:25:01+02:00'
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->updateCompetitor($token, $data['id'], $data['name']);
        $this->assertTrue($updateResult);
    }

    public function testDeleteCompetitor()
    {
        $token = 'test-token';

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->deleteCompetitor($token, 25);
        $this->assertTrue($updateResult);
    }

    public function testGetHousings()
    {
        $token = 'some-token';
        $data = [
            [
                'id' => 1,
                'name' => 'Test',
                'address' => 'Test address',
                'buildings' => [
                    [
                        'floors' => 2,
                        'type' => 4,
                        'id' => 'f6098949-b711-49c6-a16a-3105861a7b32',
                        'name' => 'Test Housing',
                        'google_place_id' => 'test',
                        'photos' => [
                            [
                                'id' => 29,
                                'link' => 'daweawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:52+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:52+02:00'
                            ],
                            [
                                'id' => 30,
                                'link' => 'second',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:52+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:52+02:00'
                            ]
                        ],
                        'housing' => [
                            'id'=> 11,
                            'name' => 'Test',
                            'address' => 'sdiafhiuhgsdihgioushdgi',
                            'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'created_at' => '2018-02-20T12:25:01+02:00',
                            'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'updated_at' => '2018-02-20T12:25:01+02:00'
                        ],
                        'agreements' => [
                            [
                                'id' => 16,
                                'files' => [
                                    [
                                        'id' => 26,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ],
                                    [
                                        'id' => 27,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ]
                                ],
                                'sign_date' => '2018-01-29T17:02:05+02:00',
                                'expires' => '2018-01-29T17:02:05+02:00',
                                'auto_extend' => false,
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'competitors' => [
                            [
                                'id' => 14,
                                'name' => 'Test Competitor',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:06:13+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:06:13+02:00'
                            ]
                        ],
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:10:11+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:10:11+02:00',
                        'object_type' => 'BH'
                    ]
                ],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T12:34:52+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T12:34:52+02:00'
            ],
            [
                'id' => 2,
                'name' => 'Testing',
                'address' => 'New address',
                'buildings' => [],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T12:34:52+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T12:34:52+02:00'
            ]
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $housings = $buildingsFacade->getHousings($token);
        $this->assertContainsOnlyInstancesOf(Housing::class, $housings);
        $this->assertHouse($data[0]['buildings'][0], $housings[0]->buildings[0]);
    }

    public function testGetHousing()
    {
        $token = 'some-token';
        $data = [
            'id' => 1,
            'name' => 'Test',
            'address' => 'Test address',
            'buildings' => [
                [
                    'floors' => 2,
                    'type' => 4,
                    'id' => 'f6098949-b711-49c6-a16a-3105861a7b32',
                    'name' => 'Test Housing',
                    'google_place_id' => 'test',
                    'photos' => [
                        [
                            'id' => 29,
                            'link' => 'daweawdaw',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:52+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:52+02:00'
                        ],
                        [
                            'id' => 30,
                            'link' => 'second',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:52+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:52+02:00'
                        ]
                    ],
                    'housing' => [
                        'id'=> 11,
                        'name' => 'Test',
                        'address' => 'sdiafhiuhgsdihgioushdgi',
                        'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                        'created_at' => '2018-02-20T12:25:01+02:00',
                        'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                        'updated_at' => '2018-02-20T12:25:01+02:00'
                    ],
                    'agreements' => [
                        [
                            'id' => 16,
                            'files' => [
                                [
                                    'id' => 26,
                                    'link' => 'dawdaw',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T12:34:16+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T12:34:16+02:00'
                                ],
                                [
                                    'id' => 27,
                                    'link' => 'dawdaw',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T12:34:16+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T12:34:16+02:00'
                                ]
                            ],
                            'sign_date' => '2018-01-29T17:02:05+02:00',
                            'expires' => '2018-01-29T17:02:05+02:00',
                            'auto_extend' => false,
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:16+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:16+02:00'
                        ]
                    ],
                    'competitors' => [
                        [
                            'id' => 14,
                            'name' => 'Test Competitor',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:06:13+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:06:13+02:00'
                        ]
                    ],
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:10:11+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:10:11+02:00',
                    'object_type' => 'BH'
                ]
            ],
            'created_by' => 'admin',
            'created_at' => '2018-02-20T12:34:52+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T12:34:52+02:00'
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $housing = $buildingsFacade->getHousing($token, 1);
        $this->assertHouse($data['buildings'][0], $housing->buildings[0]);
    }

    public function testNewHousing()
    {
        $token = 'some-token';
        $data = [
            'id' => 1,
            'name' => 'Test',
            'address' => 'Test address',
            'buildings' => [
                [
                    'floors' => 2,
                    'type' => 4,
                    'id' => 'f6098949-b711-49c6-a16a-3105861a7b32',
                    'name' => 'Test Housing',
                    'google_place_id' => 'test',
                    'buildings' => [],
                    'photos' => [],
                    'housing' => null,
                    'agreements' => [],
                    'competitors' => [],
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:10:11+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:10:11+02:00',
                    'object_type' => 'BH'
                ],
                [
                    'floors' => 30,
                    'type' => 2,
                    'rooms' => 3050,
                    'sections' => [],
                    'id' => '32c732e4-6fb0-476c-b4f6-7c9b895fea7a',
                    'name' => 'Test 2',
                    'google_place_id' => 'new testing',
                    'buildings' => [],
                    'photos' => [],
                    'housing' => null,
                    'agreements' => [],
                    'competitors' => [],
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:40:41+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:40:41+02:00',
                    'object_type' => 'BGH'
                ]
            ],
            'created_by' => 'admin',
            'created_at' => '2018-02-20T12:34:52+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T12:34:52+02:00'
        ];

        $client = $this->createClient($data, 200);

        $buildingsFacade   = new BuildingsFacade($client, self::$baseApiUrl);
        $newHousing = $buildingsFacade->newHousing($token, $data['name'], $data['address']);

        $this->assertInstanceOf(Housing::class, $newHousing);
        $this->assertEquals($data['name'], $newHousing->name);
        $this->assertEquals(new \DateTimeImmutable($data['created_at']), $newHousing->createdAt);
        $this->assertEquals($data['edited_by'], $newHousing->editedBy);
        $this->assertEquals($data['created_by'], $newHousing->createdBy);
        $this->assertEquals(new \DateTimeImmutable($data['updated_at']), $newHousing->updatedAt);
        $this->assertEquals($data['address'], $newHousing->address);
        $this->assertContainsOnlyInstancesOf(Building::class, $newHousing->buildings);
        $this->assertInstanceOf(House::class, $newHousing->buildings[0]);
        $this->assertInstanceOf(MultiApartment::class, $newHousing->buildings[1]);
    }

    public function testUpdateHousing()
    {
        $token = 'test-token';
        $data  = [
            'id' => 1,
            'name' => 'Test',
            'address' => 'Test address'
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->updateHousing($token, $data['id'], $data['name'], $data['address']);
        $this->assertTrue($updateResult);
    }

    public function testDeleteHousing()
    {
        $token = 'test-token';

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->deleteHousing($token, 25);
        $this->assertTrue($updateResult);
    }

    public function testGetHouses()
    {
        $token = 'some-token';
        $data = [
            [
                'floors' => 2,
                'type' => 4,
                'id' => 'f6098949-b711-49c6-a16a-3105861a7b32',
                'name' => 'Test Housing',
                'google_place_id' => 'test',
                'photos' => [
                    [
                        'id' => 29,
                        'link' => 'daweawdaw',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ],
                    [
                        'id' => 30,
                        'link' => 'second',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ]
                ],
                'housing' => [
                    'id'=> 11,
                    'name' => 'Test',
                    'address' => 'sdiafhiuhgsdihgioushdgi',
                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'created_at' => '2018-02-20T12:25:01+02:00',
                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'updated_at' => '2018-02-20T12:25:01+02:00'
                ],
                'agreements' => [
                    [
                        'id' => 16,
                        'files' => [
                            [
                                'id' => 26,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ],
                            [
                                'id' => 27,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'sign_date' => '2018-01-29T17:02:05+02:00',
                        'expires' => '2018-01-29T17:02:05+02:00',
                        'auto_extend' => false,
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:16+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:16+02:00'
                    ]
                ],
                'competitors' => [
                    [
                        'id' => 14,
                        'name' => 'Test Competitor',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:06:13+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:06:13+02:00'
                    ]
                ],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T14:10:11+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T14:10:11+02:00',
                'object_type' => 'BH'
            ],
            [
                'floors' => 2,
                'type' => 4,
                'id' => 'f6098949-b711-49c6-a16a-3105861a7b32',
                'name' => 'Test Housing',
                'google_place_id' => 'test',
                'photos' => [
                    [
                        'id' => 29,
                        'link' => 'daweawdaw',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ],
                    [
                        'id' => 30,
                        'link' => 'second',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ]
                ],
                'housing' => [
                    'id'=> 11,
                    'name' => 'Test',
                    'address' => 'sdiafhiuhgsdihgioushdgi',
                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'created_at' => '2018-02-20T12:25:01+02:00',
                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'updated_at' => '2018-02-20T12:25:01+02:00'
                ],
                'agreements' => [
                    [
                        'id' => 16,
                        'files' => [
                            [
                                'id' => 26,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ],
                            [
                                'id' => 27,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'sign_date' => '2018-01-29T17:02:05+02:00',
                        'expires' => '2018-01-29T17:02:05+02:00',
                        'auto_extend' => false,
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:16+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:16+02:00'
                    ]
                ],
                'competitors' => [
                    [
                        'id' => 14,
                        'name' => 'Test Competitor',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:06:13+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:06:13+02:00'
                    ]
                ],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T14:10:11+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T14:10:11+02:00',
                'object_type' => 'BH'
            ]
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $houses = $buildingsFacade->getHouses($token);
        $this->assertContainsOnlyInstancesOf(House::class, $houses);
        $this->assertHouse($data[0], $houses[0]);
        $this->assertHouse($data[1], $houses[1]);
    }

    public function testGetHouse()
    {
        $token = 'some-token';
        $data = [
            'floors' => 2,
            'type' => 4,
            'id' => 'f6098949-b711-49c6-a16a-3105861a7b32',
            'name' => 'Test Housing',
            'google_place_id' => 'test',
            'photos' => [
                [
                    'id' => 29,
                    'link' => 'daweawdaw',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:52+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:52+02:00'
                ],
                [
                    'id' => 30,
                    'link' => 'second',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:52+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:52+02:00'
                ]
            ],
            'housing' => [
                'id'=> 11,
                'name' => 'Test',
                'address' => 'sdiafhiuhgsdihgioushdgi',
                'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                'created_at' => '2018-02-20T12:25:01+02:00',
                'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                'updated_at' => '2018-02-20T12:25:01+02:00'
            ],
            'agreements' => [
                [
                    'id' => 16,
                    'files' => [
                        [
                            'id' => 26,
                            'link' => 'dawdaw',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:16+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:16+02:00'
                        ],
                        [
                            'id' => 27,
                            'link' => 'dawdaw',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:16+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:16+02:00'
                        ]
                    ],
                    'sign_date' => '2018-01-29T17:02:05+02:00',
                    'expires' => '2018-01-29T17:02:05+02:00',
                    'auto_extend' => false,
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:16+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:16+02:00'
                ]
            ],
            'competitors' => [
                [
                    'id' => 14,
                    'name' => 'Test Competitor',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:06:13+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:06:13+02:00'
                ]
            ],
            'created_by' => 'admin',
            'created_at' => '2018-02-20T14:10:11+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T14:10:11+02:00',
            'object_type' => 'BH'
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $house = $buildingsFacade->getHouse($token, '1');
        $this->assertHouse($data, $house);
    }

    public function testNewHouse()
    {
        $token = 'some-token';
        $data = [
            'floors' => 2,
            'type' => 4,
            'id' => 'f6098949-b711-49c6-a16a-3105861a7b32',
            'name' => 'Test Housing',
            'google_place_id' => 'test',
            'photos' => [],
            'housing' => null,
            'agreements' => [],
            'competitors' => [],
            'created_by' => 'admin',
            'created_at' => '2018-02-20T14:10:11+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T14:10:11+02:00',
            'object_type' => 'BH'
        ];

        $client = $this->createClient($data, 200);

        $buildingsFacade   = new BuildingsFacade($client, self::$baseApiUrl);
        $newHouse = $buildingsFacade->newHouse($token, $data['name'], $data['floors'], $data['google_place_id'], $data['object_type']);

        $this->assertInstanceOf(House::class, $newHouse);
        $this->assertEquals($data['name'], $newHouse->name);
        $this->assertEquals(new \DateTimeImmutable($data['created_at']), $newHouse->createdAt);
        $this->assertEquals($data['edited_by'], $newHouse->editedBy);
        $this->assertEquals($data['created_by'], $newHouse->createdBy);
        $this->assertEquals(new \DateTimeImmutable($data['updated_at']), $newHouse->updatedAt);
        $this->assertEquals($data['floors'], $newHouse->floors);
        $this->assertEquals($data['id'], $newHouse->id);
        $this->assertEquals($data['google_place_id'], $newHouse->googlePlaceId);
        $this->assertEquals($data['photos'], $newHouse->photos);
        $this->assertEquals($data['housing'], $newHouse->housing);
        $this->assertEquals($data['agreements'], $newHouse->agreements);
        $this->assertEquals($data['competitors'], $newHouse->competitors);
    }

    public function testUpdateHouse()
    {
        $token = 'test-token';
        $data  = [
            'floors' => 2,
            'type' => 4,
            'id' => 'f6098949-b711-49c6-a16a-3105861a7b32',
            'name' => 'Test Housing',
            'google_place_id' => 'test',
            'photos' => [],
            'housing' => null,
            'agreements' => [],
            'competitors' => [],
            'created_by' => 'admin',
            'created_at' => '2018-02-20T14:10:11+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T14:10:11+02:00',
            'object_type' => 'BH'
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->updateHouse($token, $data['id'], $data['name'], $data['floors'], $data['google_place_id'], $data['object_type']);
        $this->assertTrue($updateResult);
    }

    public function testDeleteBuilding()
    {
        $token = 'test-token';

        $data = [
            'building_id' => '25'
        ];

        $buildings = [
            'deleteHouse',
            'deleteMultiApartment'
        ];

        foreach ($buildings as $building) {
            $client       = $this->createClient(null, 200);
            $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
            $updateResult = $buildingsFacade->$building($token, $data['building_id']);
            $this->assertTrue($updateResult);
        }
    }

    public function testDeleteComplex()
    {
        $token = 'test-token';

        $data = [
            'complex_id' => '25',
            'with_multi_apartment' => false
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->deleteComplex($token, $data['complex_id'], $data['with_multi_apartment']);
        $this->assertTrue($updateResult);
    }

    public function testAddCompetitorToBuilding()
    {
        $token = 'test-token';
        $data = [
            'building_id' => '25',
            'competitor_id' => 2
        ];

        $buildings = [
            'addCompetitorToHouse',
            'addCompetitorToComplex',
            'addCompetitorToMultiApartment'
        ];

        foreach ($buildings as $building) {
            $client       = $this->createClient(null, 200);
            $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
            $updateResult = $buildingsFacade->$building($token, $data['building_id'], $data['competitor_id']);
            $this->assertTrue($updateResult);
        }
    }

    public function testRemoveCompetitorFromBuilding()
    {
        $token = 'test-token';
        $data = [
            'building_id' => '25',
            'competitor_id' => 2
        ];

        $buildings = [
            'removeCompetitorFromHouse',
            'removeCompetitorFromComplex',
            'removeCompetitorFromMultiApartment'
        ];

        foreach ($buildings as $building) {
            $client       = $this->createClient(null, 200);
            $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
            $updateResult = $buildingsFacade->$building($token, $data['building_id'], $data['competitor_id']);
            $this->assertTrue($updateResult);
        }
    }

    public function testNewAgreementInBuilding()
    {
        $token = 'some-token';
        $data = [
            'files' => [
                [
                    'id' => 43,
                    'link' => 'dawdaw',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:13:17+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:13:17+02:00'
                ],
                [
                    'id' => 44,
                    'link' => 'dawdaw',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:13:17+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:13:17+02:00'
                ],
                [
                    'id' => 45,
                    'link' => 'dawewa',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:13:17+02:00',
                    'edited_by' => 'admin',
                    'updated_at'=> '2018-02-20T14:13:17+02:00'
                ]
            ],
            'id' => 19,
            'sign_date' => '2018-01-29T17:02:05+02:00',
            'expires' => '2018-01-29T17:02:05+02:00',
            'auto_extend' => false,
            'created_by' => 'admin',
            'created_at' => '2018-02-20T14:13:17+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T14:13:17+02:00',
            'attachments' => [
                'dawdaw',
                'dawdaw',
                'dawewa'
            ],
            'building_id' => '25'
        ];

        $buildings = [
            'newAgreementInHouse',
            'newAgreementInComplex',
            'newAgreementInMultiApartment'
        ];

        foreach ($buildings as $building) {
            $client = $this->createClient($data, 200);

            $buildingsFacade   = new BuildingsFacade($client, self::$baseApiUrl);
            $newAgreementInBuilding = $buildingsFacade->$building(
                $token,
                $data['building_id'],
                $data['auto_extend'],
                new \DateTimeImmutable($data['expires']),
                new \DateTimeImmutable($data['sign_date']),
                $data['attachments']
            );

            $this->assertInstanceOf(Agreement::class, $newAgreementInBuilding);
            $this->assertContainsOnlyInstancesOf(Attachment::class, $newAgreementInBuilding->files);
            $this->assertEquals(new \DateTimeImmutable($data['created_at']), $newAgreementInBuilding->createdAt);
            $this->assertEquals($data['edited_by'], $newAgreementInBuilding->editedBy);
            $this->assertEquals($data['created_by'], $newAgreementInBuilding->createdBy);
            $this->assertEquals(new \DateTimeImmutable($data['updated_at']), $newAgreementInBuilding->updatedAt);
            $this->assertEquals(new \DateTimeImmutable($data['sign_date']), $newAgreementInBuilding->signDate);
            $this->assertEquals(new \DateTimeImmutable($data['expires']), $newAgreementInBuilding->expires);
            $this->assertEquals($data['id'], $newAgreementInBuilding->id);
        }
    }

    public function testUpdateAgreementInBuilding()
    {
        $token = 'test-token';
        $data  = [
            'id' => '1',
            'agreement_id' => 2,
            'sign_date' => '2018-02-20T14:10:11+02:00',
            'expires' => '2018-02-20T14:10:11+02:00',
            'auto_extend' => true,
            'attachments' => [
                'dawdaw',
                'dawdaw',
                'dawewa'
            ]
        ];

        $buildings = [
            'updateAgreementInHouse',
            'updateAgreementInComplex',
            'updateAgreementInMultiApartment'
        ];

        foreach ($buildings as $building) {
            $client       = $this->createClient(null, 200);
            $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
            $updateResult = $buildingsFacade->$building(
                $token,
                $data['id'],
                $data['agreement_id'],
                $data['auto_extend'],
                new \DateTimeImmutable($data['sign_date']),
                new \DateTimeImmutable($data['expires']),
                $data['attachments']
            );
            $this->assertTrue($updateResult);

        }
    }

    public function testDeleteAgreementInBuilding()
    {
        $token = 'test-token';
        $data = [
            'building_id' => '25',
            'agreement_id' => 2
        ];

        $buildings = [
            'deleteAgreementInHouse',
            'deleteAgreementInComplex',
            'deleteAgreementInMultiApartment'
        ];

        foreach ($buildings as $building) {
            $client       = $this->createClient(null, 200);
            $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
            $updateResult = $buildingsFacade->$building($token, $data['building_id'], $data['agreement_id']);
            $this->assertTrue($updateResult);
        }
    }

    public function testAddHousingToBuilding()
    {
        $token = 'test-token';
        $data = [
            'building_id' => '25',
            'housing_id' => 2
        ];

        $buildings = [
            'addHousingToComplex',
            'addHousingToHouse',
            'addHousingToMultiApartment'
        ];

        foreach ($buildings as $building) {
            $client       = $this->createClient(null, 200);
            $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
            $updateResult = $buildingsFacade->$building($token, $data['building_id'], $data['housing_id']);
            $this->assertTrue($updateResult);
        }
    }

    public function testRemoveHousingFromBuilding()
    {
        $token = 'test-token';
        $data = [
            'building_id' => '25',
            'housing_id' => 2
        ];

        $buildings = [
            'removeHousingFromComplex',
            'removeHousingFromHouse',
            'removeHousingFromMultiApartment'
        ];

        foreach ($buildings as $building) {
            $client       = $this->createClient(null, 200);
            $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
            $updateResult = $buildingsFacade->$building($token, $data['building_id'], $data['housing_id']);
            $this->assertTrue($updateResult);
        }
    }

    public function testNewAttachmentInBuilding()
    {
        $token = 'test-token';
        $data = [
            'building_id' => '25',
            'attachments' => [
                [
                    'id' => 51,
                    'link' => '321dadwa',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:17:46+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:17:46+02:00'
                ],
                [
                    'id' => 52,
                    'link' => '321dadwa2',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:17:46+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:17:46+02:00'
                ]
            ]
        ];

        $attachments = [
            'test1',
            'test2'
        ];

        $buildings = [
            'newAttachmentInComplex',
            'newAttachmentInHouse',
            'newAttachmentInMultiApartment'
        ];

        foreach ($buildings as $building) {
            $client = $this->createClient($data['attachments'], 200);

            $buildingsFacade   = new BuildingsFacade($client, self::$baseApiUrl);
            $newAttachment = $buildingsFacade->$building(
                $token,
                $data['building_id'],
                $attachments
            );

            $this->assertContainsOnlyInstancesOf(Attachment::class, $newAttachment);
            $this->assertEquals($data['attachments'][0]['id'], $newAttachment[0]->id);
            $this->assertEquals($data['attachments'][1]['id'], $newAttachment[1]->id);
            $this->assertEquals($data['attachments'][0]['link'], $newAttachment[0]->link);
            $this->assertEquals($data['attachments'][1]['link'], $newAttachment[1]->link);
            $this->assertEquals($data['attachments'][0]['created_by'], $newAttachment[0]->createdBy);
            $this->assertEquals(new \DateTimeImmutable($data['attachments'][0]['created_at']), $newAttachment[0]->createdAt);
            $this->assertEquals($data['attachments'][0]['edited_by'], $newAttachment[0]->editedBy);
            $this->assertEquals(new \DateTimeImmutable($data['attachments'][0]['updated_at']), $newAttachment[0]->updatedAt);
            $this->assertEquals($data['attachments'][1]['created_by'], $newAttachment[1]->createdBy);
            $this->assertEquals(new \DateTimeImmutable($data['attachments'][1]['created_at']), $newAttachment[1]->createdAt);
            $this->assertEquals($data['attachments'][1]['edited_by'], $newAttachment[1]->editedBy);
            $this->assertEquals(new \DateTimeImmutable($data['attachments'][1]['updated_at']), $newAttachment[1]->updatedAt);
        }
    }

    public function testDeleteAttachmentInBuilding()
    {
        $token = 'test-token';
        $data = [
            'building_id' => '25',
            'attachment_id' => 2
        ];

        $buildings = [
            'deleteAttachmentInComplex',
            'deleteAttachmentInHouse',
            'deleteAttachmentInMultiApartment'
        ];

        foreach ($buildings as $building) {
            $client       = $this->createClient(null, 200);
            $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
            $updateResult = $buildingsFacade->$building($token, $data['building_id'], $data['attachment_id']);
            $this->assertTrue($updateResult);
        }
    }

    public function testGetMultiApartments()
    {
        $token = 'some-token';
        $data = [
            [
                'floors' => 30,
                'type' => 2,
                'rooms' => 3050,
                'sections' => [
                    [
                        'floors' => null,
                        'rooms' => null,
                        'section_number' => null,
                        'type' => 3,
                        'id' => 'b65e6647-1749-4ab8-8c0e-21909533f23c',
                        'name' => '321dwadaw',
                        'google_place_id' => null,
                        'photos' => [
                            [
                                'id' => 53,
                                'link' => '321dadwa',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:18:48+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:18:48+02:00'
                            ],
                            [
                                'id' => 54,
                                'link' => '321dadwa2',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:18:48+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:18:48+02:00'
                            ]
                        ],
                        'housing' => [
                            'id'=> 11,
                            'name' => 'Test',
                            'address' => 'sdiafhiuhgsdihgioushdgi',
                            'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'created_at' => '2018-02-20T12:25:01+02:00',
                            'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'updated_at' => '2018-02-20T12:25:01+02:00'
                        ],
                        'agreements' => [
                            [
                                'id' => 16,
                                'files' => [
                                    [
                                        'id' => 26,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ],
                                    [
                                        'id' => 27,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ]
                                ],
                                'sign_date' => '2018-01-29T17:02:05+02:00',
                                'expires' => '2018-01-29T17:02:05+02:00',
                                'auto_extend' => false,
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'competitors' => [
                            [
                                'id' => 14,
                                'name' => 'Test Competitor',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:06:13+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:06:13+02:00'
                            ]
                        ],
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:18:05+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:18:48+02:00',
                        'object_type' => null
                    ]
                ],
                'id' => '32c732e4-6fb0-476c-b4f6-7c9b895fea7a',
                'name' => 'Test 2',
                'google_place_id' => 'new testing',
                'photos' => [
                    [
                        'id' => 29,
                        'link' => 'daweawdaw',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ],
                    [
                        'id' => 30,
                        'link' => 'second',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ]
                ],
                'housing' => [
                    'id'=> 11,
                    'name' => 'Test',
                    'address' => 'sdiafhiuhgsdihgioushdgi',
                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'created_at' => '2018-02-20T12:25:01+02:00',
                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'updated_at' => '2018-02-20T12:25:01+02:00'
                ],
                'agreements' => [
                    [
                        'id' => 16,
                        'files' => [
                            [
                                'id' => 26,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ],
                            [
                                'id' => 27,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'sign_date' => '2018-01-29T17:02:05+02:00',
                        'expires' => '2018-01-29T17:02:05+02:00',
                        'auto_extend' => false,
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:16+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:16+02:00'
                    ]
                ],
                'competitors' => [
                    [
                        'id' => 14,
                        'name' => 'Test Competitor',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:06:13+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:06:13+02:00'
                    ]
                ],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T12:40:41+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T12:40:41+02:00',
                'object_type' => 'BGH'
            ],
            [
                'floors' => 30,
                'type' => 2,
                'rooms' => 3050,
                'sections' => [
                    [
                        'floors' => null,
                        'rooms' => null,
                        'section_number' => null,
                        'type' => 3,
                        'id' => 'b65e6647-1749-4ab8-8c0e-21909533f23c',
                        'name' => '321dwadaw',
                        'google_place_id' => null,
                        'photos' => [
                            [
                                'id' => 53,
                                'link' => '321dadwa',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:18:48+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:18:48+02:00'
                            ],
                            [
                                'id' => 54,
                                'link' => '321dadwa2',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:18:48+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:18:48+02:00'
                            ]
                        ],
                        'housing' => [
                            'id'=> 11,
                            'name' => 'Test',
                            'address' => 'sdiafhiuhgsdihgioushdgi',
                            'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'created_at' => '2018-02-20T12:25:01+02:00',
                            'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'updated_at' => '2018-02-20T12:25:01+02:00'
                        ],
                        'agreements' => [
                            [
                                'id' => 16,
                                'files' => [
                                    [
                                        'id' => 26,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ],
                                    [
                                        'id' => 27,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ]
                                ],
                                'sign_date' => '2018-01-29T17:02:05+02:00',
                                'expires' => '2018-01-29T17:02:05+02:00',
                                'auto_extend' => false,
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'competitors' => [
                            [
                                'id' => 14,
                                'name' => 'Test Competitor',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:06:13+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:06:13+02:00'
                            ]
                        ],
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:18:05+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:18:48+02:00',
                        'object_type' => null
                    ]
                ],
                'id' => '32c732e4-6fb0-476c-b4f6-7c9b895fea7a',
                'name' => 'Test 2',
                'google_place_id' => 'new testing',
                'photos' => [
                    [
                        'id' => 29,
                        'link' => 'daweawdaw',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ],
                    [
                        'id' => 30,
                        'link' => 'second',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ]
                ],
                'housing' => [
                    'id'=> 11,
                    'name' => 'Test',
                    'address' => 'sdiafhiuhgsdihgioushdgi',
                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'created_at' => '2018-02-20T12:25:01+02:00',
                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'updated_at' => '2018-02-20T12:25:01+02:00'
                ],
                'agreements' => [
                    [
                        'id' => 16,
                        'files' => [
                            [
                                'id' => 26,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ],
                            [
                                'id' => 27,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'sign_date' => '2018-01-29T17:02:05+02:00',
                        'expires' => '2018-01-29T17:02:05+02:00',
                        'auto_extend' => false,
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:16+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:16+02:00'
                    ]
                ],
                'competitors' => [
                    [
                        'id' => 14,
                        'name' => 'Test Competitor',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:06:13+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:06:13+02:00'
                    ]
                ],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T12:40:41+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T12:40:41+02:00',
                'object_type' => 'BGH'
            ]
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $multiApartments = $buildingsFacade->getMultiApartments($token);
        $this->assertContainsOnlyInstancesOf(MultiApartment::class, $multiApartments);

        foreach ($multiApartments as $key => $multiApartment) {
            $this->assertMultiApartment($data[$key], $multiApartment);
        }
    }

    public function testGetMultiApartment()
    {
        $token = 'some-token';
        $data = [
            'floors' => 30,
            'type' => 2,
            'rooms' => 3050,
            'sections' => [
                [
                    'floors' => null,
                    'rooms' => null,
                    'section_number' => null,
                    'type' => 3,
                    'id' => 'b65e6647-1749-4ab8-8c0e-21909533f23c',
                    'name' => '321dwadaw',
                    'google_place_id' => null,
                    'photos' => [
                        [
                            'id' => 53,
                            'link' => '321dadwa',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:18:48+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:18:48+02:00'
                        ],
                        [
                            'id' => 54,
                            'link' => '321dadwa2',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:18:48+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:18:48+02:00'
                        ]
                    ],
                    'housing' => [
                        'id'=> 11,
                        'name' => 'Test',
                        'address' => 'sdiafhiuhgsdihgioushdgi',
                        'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                        'created_at' => '2018-02-20T12:25:01+02:00',
                        'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                        'updated_at' => '2018-02-20T12:25:01+02:00'
                    ],
                    'agreements' => [
                        [
                            'id' => 16,
                            'files' => [
                                [
                                    'id' => 26,
                                    'link' => 'dawdaw',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T12:34:16+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T12:34:16+02:00'
                                ],
                                [
                                    'id' => 27,
                                    'link' => 'dawdaw',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T12:34:16+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T12:34:16+02:00'
                                ]
                            ],
                            'sign_date' => '2018-01-29T17:02:05+02:00',
                            'expires' => '2018-01-29T17:02:05+02:00',
                            'auto_extend' => false,
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:16+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:16+02:00'
                        ]
                    ],
                    'competitors' => [
                        [
                            'id' => 14,
                            'name' => 'Test Competitor',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:06:13+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:06:13+02:00'
                        ]
                    ],
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:18:05+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:18:48+02:00',
                    'object_type' => null
                ]
            ],
            'id' => '32c732e4-6fb0-476c-b4f6-7c9b895fea7a',
            'name' => 'Test 2',
            'google_place_id' => 'new testing',
            'photos' => [
                [
                    'id' => 29,
                    'link' => 'daweawdaw',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:52+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:52+02:00'
                ],
                [
                    'id' => 30,
                    'link' => 'second',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:52+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:52+02:00'
                ]
            ],
            'housing' => [
                'id'=> 11,
                'name' => 'Test',
                'address' => 'sdiafhiuhgsdihgioushdgi',
                'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                'created_at' => '2018-02-20T12:25:01+02:00',
                'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                'updated_at' => '2018-02-20T12:25:01+02:00'
            ],
            'agreements' => [
                [
                    'id' => 16,
                    'files' => [
                        [
                            'id' => 26,
                            'link' => 'dawdaw',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:16+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:16+02:00'
                        ],
                        [
                            'id' => 27,
                            'link' => 'dawdaw',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:16+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:16+02:00'
                        ]
                    ],
                    'sign_date' => '2018-01-29T17:02:05+02:00',
                    'expires' => '2018-01-29T17:02:05+02:00',
                    'auto_extend' => false,
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:16+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:16+02:00'
                ]
            ],
            'competitors' => [
                [
                    'id' => 14,
                    'name' => 'Test Competitor',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:06:13+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:06:13+02:00'
                ]
            ],
            'created_by' => 'admin',
            'created_at' => '2018-02-20T12:40:41+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T12:40:41+02:00',
            'object_type' => 'BGH'
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $multiApartment = $buildingsFacade->getMultiApartment($token, '1');
        $this->assertMultiApartment($data, $multiApartment);
    }

    public function testNewMultiApartment()
    {
        $token = 'some-token';
        $data = [
            'id' => '25',
            'name' => 'Test',
            'floors' => 25,
            'rooms' => 2560,
            'type' => 2,
            'sections' => [],
            'photos' => [],
            'housing' => null,
            'agreements' => [],
            'competitors' => [],
            'google_place_id' => 'test',
            'created_by' => 'admin',
            'created_at' => '2018-02-20T12:25:01+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T12:25:01+02:00',
            'object_type' => 'BH'
        ];

        $client = $this->createClient($data, 200);

        $buildingsFacade   = new BuildingsFacade($client, self::$baseApiUrl);
        $newMultiApartment = $buildingsFacade->newMultiApartment($token, $data['name']);

        $this->assertInstanceOf(MultiApartment::class, $newMultiApartment);
        $this->assertEquals($data['name'], $newMultiApartment->name);
        $this->assertEquals(new \DateTimeImmutable($data['created_at']), $newMultiApartment->createdAt);
        $this->assertEquals($data['edited_by'], $newMultiApartment->editedBy);
        $this->assertEquals($data['created_by'], $newMultiApartment->createdBy);
        $this->assertEquals(new \DateTimeImmutable($data['updated_at']), $newMultiApartment->updatedAt);
        $this->assertEquals($data['floors'], $newMultiApartment->floors);
        $this->assertEquals($data['rooms'], $newMultiApartment->rooms);
        $this->assertEquals($data['google_place_id'], $newMultiApartment->googlePlaceId);
        $this->assertEquals($data['type'], $newMultiApartment->type);
        $this->assertEquals($data['object_type'], $newMultiApartment->objectType);
    }

    public function testUpdateMultiApartment()
    {
        $token = 'test-token';
        $data  = [
            'id' => '1'
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->updateMultiApartment($token, $data['id']);
        $this->assertTrue($updateResult);
    }

    public function testNewSectionInMultiApartment()
    {
        $token = 'some-token';
        $data = [
            'id' => '25',
            'name' => 'Test',
            'floors' => 25,
            'rooms' => 2560,
            'type' => 2,
            'sections' => [],
            'photos' => [],
            'housing' => null,
            'agreements' => [],
            'competitors' => [],
            'google_place_id' => 'test',
            'created_by' => 'admin',
            'created_at' => '2018-02-20T12:25:01+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T12:25:01+02:00',
            'object_type' => 'BH'
        ];

        $section = [
            'section_number' => '2b',
            'floors' => 25,
            'rooms' => 20500,
            'google_place_id' => 'test',
            'object_type' => 'BH',
            'name' => 'Test Section'
        ];

        $client = $this->createClient($data, 200);

        $buildingsFacade   = new BuildingsFacade($client, self::$baseApiUrl);
        $newMultiApartment = $buildingsFacade->newSectionInMultiApartment(
            $token,
            $data['id'],
            $section['name'],
            $section['section_number'],
            $section['floors'],
            $section['rooms'],
            $section['google_place_id'],
            $section['object_type']
        );

        $this->assertInstanceOf(MultiApartment::class, $newMultiApartment);
        $this->assertEquals($data['name'], $newMultiApartment->name);
        $this->assertEquals(new \DateTimeImmutable($data['created_at']), $newMultiApartment->createdAt);
        $this->assertEquals($data['edited_by'], $newMultiApartment->editedBy);
        $this->assertEquals($data['created_by'], $newMultiApartment->createdBy);
        $this->assertEquals(new \DateTimeImmutable($data['updated_at']), $newMultiApartment->updatedAt);
        $this->assertEquals($data['floors'], $newMultiApartment->floors);
        $this->assertEquals($data['rooms'], $newMultiApartment->rooms);
        $this->assertEquals($data['google_place_id'], $newMultiApartment->googlePlaceId);
        $this->assertEquals($data['type'], $newMultiApartment->type);
        $this->assertEquals($data['object_type'], $newMultiApartment->objectType);
        $this->assertContainsOnlyInstancesOf(Section::class, $newMultiApartment->sections);
    }

    public function testUpdateSectionInMultiApartment()
    {
        $token = 'test-token';
        $data  = [
            'floors' => 2,
            'multiApartmentId' => 'f6098949-b711-49c6-a16a-3105861a7b32',
            'sectionId' => 'f6098949-b711-49c6-a16a-3105861a7b32',
            'name' => 'Test Housing',
            'google_place_id' => 'test',
            'object_type' => 'BH',
            'section_number' => '2b',
            'rooms' => 250
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->updateSectionInMultiApartment(
            $token,
            $data['multiApartmentId'],
            $data['sectionId'],
            $data['name'],
            $data['section_number'],
            $data['floors'],
            $data['rooms'],
            $data['google_place_id'],
            $data['object_type']
        );
        $this->assertTrue($updateResult);
    }

    public function testDeleteSectionInMultiApartment()
    {
        $token = 'test-token';
        $data = [
            'multi_apartment_id' => '25',
            'section_id' => '2'
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->deleteSectionInMultiApartment($token, $data['multi_apartment_id'], $data['section_id']);
        $this->assertTrue($updateResult);
    }

    public function testNewAttachmentInSectionInMultiApartment()
    {
        $token = 'test-token';
        $data = [
            'multi_apartment_id' => '25',
            'section_id' => '25',
            'attachments' => [
                [
                    'id' => 51,
                    'link' => '321dadwa',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:17:46+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:17:46+02:00'
                ],
                [
                    'id' => 52,
                    'link' => '321dadwa2',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:17:46+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:17:46+02:00'
                ]
            ]
        ];

        $attachments = [
            'test1',
            'test2'
        ];

        $client = $this->createClient($data['attachments'], 200);

        $buildingsFacade   = new BuildingsFacade($client, self::$baseApiUrl);
        $newAttachment = $buildingsFacade->newAttachmentInSectionInMultiApartment(
            $token,
            $data['multi_apartment_id'],
            $data['section_id'],
            $attachments
        );

        $this->assertContainsOnlyInstancesOf(Attachment::class, $newAttachment);
    }

    public function testDeleteAttachmentInSectionInMultiApartment()
    {
        $token = 'test-token';
        $data = [
            'multi_apartment_id' => '25',
            'section_id' => '2',
            'attachment_id' => 4
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->deleteAttachmentInSectionInMultiApartment(
            $token,
            $data['multi_apartment_id'],
            $data['section_id'],
            $data['attachment_id']
        );
        $this->assertTrue($updateResult);
    }

    public function testGetComplexes()
    {
        $token = 'some-token';
        $data = [
            [
                'multi_apartment' => [
                    [
                        'floors' => null,
                        'rooms' => null,
                        'sections' => [
                            [
                                'floors' => null,
                                'rooms' => null,
                                'section_number' => null,
                                'type' => 3,
                                'id' => 'b65e6647-1749-4ab8-8c0e-21909533f23c',
                                'name' => '321dwadaw',
                                'google_place_id' => null,
                                'photos' => [
                                    [
                                        'id' => 53,
                                        'link' => '321dadwa',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:18:48+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:18:48+02:00'
                                    ],
                                    [
                                        'id' => 54,
                                        'link' => '321dadwa2',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:18:48+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:18:48+02:00'
                                    ]
                                ],
                                'housing' => [
                                    'id'=> 11,
                                    'name' => 'Test',
                                    'address' => 'sdiafhiuhgsdihgioushdgi',
                                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                                    'created_at' => '2018-02-20T12:25:01+02:00',
                                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                                    'updated_at' => '2018-02-20T12:25:01+02:00'
                                ],
                                'agreements' => [
                                    [
                                        'id' => 16,
                                        'files' => [
                                            [
                                                'id' => 26,
                                                'link' => 'dawdaw',
                                                'created_by' => 'admin',
                                                'created_at' => '2018-02-20T12:34:16+02:00',
                                                'edited_by' => 'admin',
                                                'updated_at' => '2018-02-20T12:34:16+02:00'
                                            ],
                                            [
                                                'id' => 27,
                                                'link' => 'dawdaw',
                                                'created_by' => 'admin',
                                                'created_at' => '2018-02-20T12:34:16+02:00',
                                                'edited_by' => 'admin',
                                                'updated_at' => '2018-02-20T12:34:16+02:00'
                                            ]
                                        ],
                                        'sign_date' => '2018-01-29T17:02:05+02:00',
                                        'expires' => '2018-01-29T17:02:05+02:00',
                                        'auto_extend' => false,
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ]
                                ],
                                'competitors' => [
                                    [
                                        'id' => 14,
                                        'name' => 'Test Competitor',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:06:13+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:06:13+02:00'
                                    ]
                                ],
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:18:05+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:18:48+02:00',
                                'object_type' => null
                            ]
                        ],
                        'type' => 2,
                        'id' => '49c9f8be-6189-4b73-97ec-365c1f4e25dd',
                        'name' => '321dwadaw',
                        'google_place_id' => null,
                        'photos' => [
                            [
                                'id' => 51,
                                'link' => '321dadwa',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:17:46+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:17:46+02:00'
                            ],
                            [
                                'id' => 52,
                                'link' => '321dadwa2',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:17:46+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:17:46+02:00'
                            ]
                        ],
                        'housing' => [
                            'id'=> 11,
                            'name' => 'Test',
                            'address' => 'sdiafhiuhgsdihgioushdgi',
                            'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'created_at' => '2018-02-20T12:25:01+02:00',
                            'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'updated_at' => '2018-02-20T12:25:01+02:00'
                        ],
                        'agreements' => [
                            [
                                'id' => 20,
                                'files' => [
                                    [
                                        'id' => 48,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:16:28+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:16:28+02:00'
                                    ],
                                    [
                                        'id' => 49,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:16:28+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:16:28+02:00'
                                    ]
                                ],
                                'sign_date' => '2018-01-29T17:02:05+02:00',
                                'expires' => '2018-01-29T17:02:05+02:00',
                                'auto_extend' => false,
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:16:28+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:16:28+02:00'
                            ]
                        ],
                        'competitors' => [
                            [
                                'id' => 14,
                                'name' => 'Test Competitor',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:06:13+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:06:13+02:00'
                            ]
                        ],
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:15:55+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-23T15:25:23+02:00',
                        'object_type' => null
                    ]
                ],
                'type' => 1,
                'id' => 'f9808785-9a36-4570-9a31-10d330b3bd05',
                'name' => '3123dadaw',
                'google_place_id' => null,
                'photos' => [
                    [
                        'id' => 29,
                        'link' => 'daweawdaw',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ],
                    [
                        'id' => 30,
                        'link' => 'second',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ]
                ],
                'housing' => [
                    'id'=> 11,
                    'name' => 'Test',
                    'address' => 'sdiafhiuhgsdihgioushdgi',
                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'created_at' => '2018-02-20T12:25:01+02:00',
                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'updated_at' => '2018-02-20T12:25:01+02:00'
                ],
                'agreements' => [
                    [
                        'id' => 16,
                        'files' => [
                            [
                                'id' => 26,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ],
                            [
                                'id' => 27,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'sign_date' => '2018-01-29T17:02:05+02:00',
                        'expires' => '2018-01-29T17:02:05+02:00',
                        'auto_extend' => false,
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:16+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:16+02:00'
                    ]
                ],
                'competitors' => [
                    [
                        'id' => 14,
                        'name' => 'Test Competitor',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:06:13+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:06:13+02:00'
                    ]
                ],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T14:47:27+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T14:47:27+02:00',
                'object_type' => null
            ],
            [
                'multi_apartment' => [
                    [
                        'floors' => null,
                        'rooms' => null,
                        'sections' => [
                            [
                                'floors' => null,
                                'rooms' => null,
                                'section_number' => null,
                                'type' => 3,
                                'id' => 'b65e6647-1749-4ab8-8c0e-21909533f23c',
                                'name' => '321dwadaw',
                                'google_place_id' => null,
                                'photos' => [
                                    [
                                        'id' => 53,
                                        'link' => '321dadwa',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:18:48+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:18:48+02:00'
                                    ],
                                    [
                                        'id' => 54,
                                        'link' => '321dadwa2',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:18:48+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:18:48+02:00'
                                    ]
                                ],
                                'housing' => [
                                    'id'=> 11,
                                    'name' => 'Test',
                                    'address' => 'sdiafhiuhgsdihgioushdgi',
                                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                                    'created_at' => '2018-02-20T12:25:01+02:00',
                                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                                    'updated_at' => '2018-02-20T12:25:01+02:00'
                                ],
                                'agreements' => [
                                    [
                                        'id' => 16,
                                        'files' => [
                                            [
                                                'id' => 26,
                                                'link' => 'dawdaw',
                                                'created_by' => 'admin',
                                                'created_at' => '2018-02-20T12:34:16+02:00',
                                                'edited_by' => 'admin',
                                                'updated_at' => '2018-02-20T12:34:16+02:00'
                                            ],
                                            [
                                                'id' => 27,
                                                'link' => 'dawdaw',
                                                'created_by' => 'admin',
                                                'created_at' => '2018-02-20T12:34:16+02:00',
                                                'edited_by' => 'admin',
                                                'updated_at' => '2018-02-20T12:34:16+02:00'
                                            ]
                                        ],
                                        'sign_date' => '2018-01-29T17:02:05+02:00',
                                        'expires' => '2018-01-29T17:02:05+02:00',
                                        'auto_extend' => false,
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T12:34:16+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T12:34:16+02:00'
                                    ]
                                ],
                                'competitors' => [
                                    [
                                        'id' => 14,
                                        'name' => 'Test Competitor',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:06:13+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:06:13+02:00'
                                    ]
                                ],
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:18:05+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:18:48+02:00',
                                'object_type' => null
                            ]
                        ],
                        'type' => 2,
                        'id' => '49c9f8be-6189-4b73-97ec-365c1f4e25dd',
                        'name' => '321dwadaw',
                        'google_place_id' => null,
                        'photos' => [
                            [
                                'id' => 51,
                                'link' => '321dadwa',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:17:46+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:17:46+02:00'
                            ],
                            [
                                'id' => 52,
                                'link' => '321dadwa2',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:17:46+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:17:46+02:00'
                            ]
                        ],
                        'housing' => [
                            'id'=> 11,
                            'name' => 'Test',
                            'address' => 'sdiafhiuhgsdihgioushdgi',
                            'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'created_at' => '2018-02-20T12:25:01+02:00',
                            'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                            'updated_at' => '2018-02-20T12:25:01+02:00'
                        ],
                        'agreements' => [
                            [
                                'id' => 20,
                                'files' => [
                                    [
                                        'id' => 48,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:16:28+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:16:28+02:00'
                                    ],
                                    [
                                        'id' => 49,
                                        'link' => 'dawdaw',
                                        'created_by' => 'admin',
                                        'created_at' => '2018-02-20T14:16:28+02:00',
                                        'edited_by' => 'admin',
                                        'updated_at' => '2018-02-20T14:16:28+02:00'
                                    ]
                                ],
                                'sign_date' => '2018-01-29T17:02:05+02:00',
                                'expires' => '2018-01-29T17:02:05+02:00',
                                'auto_extend' => false,
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:16:28+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:16:28+02:00'
                            ]
                        ],
                        'competitors' => [
                            [
                                'id' => 14,
                                'name' => 'Test Competitor',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T14:06:13+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T14:06:13+02:00'
                            ]
                        ],
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:15:55+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-23T15:25:23+02:00',
                        'object_type' => null
                    ]
                ],
                'type' => 1,
                'id' => 'f9808785-9a36-4570-9a31-10d330b3bd05',
                'name' => '3123dadaw',
                'google_place_id' => null,
                'photos' => [
                    [
                        'id' => 29,
                        'link' => 'daweawdaw',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ],
                    [
                        'id' => 30,
                        'link' => 'second',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:52+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:52+02:00'
                    ]
                ],
                'housing' => [
                    'id'=> 11,
                    'name' => 'Test',
                    'address' => 'sdiafhiuhgsdihgioushdgi',
                    'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'created_at' => '2018-02-20T12:25:01+02:00',
                    'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                    'updated_at' => '2018-02-20T12:25:01+02:00'
                ],
                'agreements' => [
                    [
                        'id' => 16,
                        'files' => [
                            [
                                'id' => 26,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ],
                            [
                                'id' => 27,
                                'link' => 'dawdaw',
                                'created_by' => 'admin',
                                'created_at' => '2018-02-20T12:34:16+02:00',
                                'edited_by' => 'admin',
                                'updated_at' => '2018-02-20T12:34:16+02:00'
                            ]
                        ],
                        'sign_date' => '2018-01-29T17:02:05+02:00',
                        'expires' => '2018-01-29T17:02:05+02:00',
                        'auto_extend' => false,
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T12:34:16+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T12:34:16+02:00'
                    ]
                ],
                'competitors' => [
                    [
                        'id' => 14,
                        'name' => 'Test Competitor',
                        'created_by' => 'admin',
                        'created_at' => '2018-02-20T14:06:13+02:00',
                        'edited_by' => 'admin',
                        'updated_at' => '2018-02-20T14:06:13+02:00'
                    ]
                ],
                'created_by' => 'admin',
                'created_at' => '2018-02-20T14:47:27+02:00',
                'edited_by' => 'admin',
                'updated_at' => '2018-02-20T14:47:27+02:00',
                'object_type' => null
            ]
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $complexes = $buildingsFacade->getComplexes($token);
        $this->assertContainsOnlyInstancesOf(Complex::class, $complexes);
        $this->assertComplex($data[0], $complexes[0]);
        $this->assertComplex($data[1], $complexes[1]);
    }

    public function testGetComplex()
    {
        $token = 'some-token';
        $data = [
            'multi_apartment' => [
                [
                    'floors' => null,
                    'rooms' => null,
                    'sections' => [
                        [
                            'floors' => null,
                            'rooms' => null,
                            'section_number' => null,
                            'type' => 3,
                            'id' => 'b65e6647-1749-4ab8-8c0e-21909533f23c',
                            'name' => '321dwadaw',
                            'google_place_id' => null,
                            'photos' => [
                                [
                                    'id' => 53,
                                    'link' => '321dadwa',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T14:18:48+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T14:18:48+02:00'
                                ],
                                [
                                    'id' => 54,
                                    'link' => '321dadwa2',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T14:18:48+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T14:18:48+02:00'
                                ]
                            ],
                            'housing' => [
                                'id'=> 11,
                                'name' => 'Test',
                                'address' => 'sdiafhiuhgsdihgioushdgi',
                                'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                                'created_at' => '2018-02-20T12:25:01+02:00',
                                'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                                'updated_at' => '2018-02-20T12:25:01+02:00'
                            ],
                            'agreements' => [
                                [
                                    'id' => 16,
                                    'files' => [
                                        [
                                            'id' => 26,
                                            'link' => 'dawdaw',
                                            'created_by' => 'admin',
                                            'created_at' => '2018-02-20T12:34:16+02:00',
                                            'edited_by' => 'admin',
                                            'updated_at' => '2018-02-20T12:34:16+02:00'
                                        ],
                                        [
                                            'id' => 27,
                                            'link' => 'dawdaw',
                                            'created_by' => 'admin',
                                            'created_at' => '2018-02-20T12:34:16+02:00',
                                            'edited_by' => 'admin',
                                            'updated_at' => '2018-02-20T12:34:16+02:00'
                                        ]
                                    ],
                                    'sign_date' => '2018-01-29T17:02:05+02:00',
                                    'expires' => '2018-01-29T17:02:05+02:00',
                                    'auto_extend' => false,
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T12:34:16+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T12:34:16+02:00'
                                ]
                            ],
                            'competitors' => [
                                [
                                    'id' => 14,
                                    'name' => 'Test Competitor',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T14:06:13+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T14:06:13+02:00'
                                ]
                            ],
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:18:05+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:18:48+02:00',
                            'object_type' => null
                        ]
                    ],
                    'type' => 2,
                    'id' => '49c9f8be-6189-4b73-97ec-365c1f4e25dd',
                    'name' => '321dwadaw',
                    'google_place_id' => null,
                    'photos' => [
                        [
                            'id' => 51,
                            'link' => '321dadwa',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:17:46+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:17:46+02:00'
                        ],
                        [
                            'id' => 52,
                            'link' => '321dadwa2',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:17:46+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:17:46+02:00'
                        ]
                    ],
                    'housing' => [
                        'id'=> 11,
                        'name' => 'Test',
                        'address' => 'sdiafhiuhgsdihgioushdgi',
                        'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                        'created_at' => '2018-02-20T12:25:01+02:00',
                        'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                        'updated_at' => '2018-02-20T12:25:01+02:00'
                    ],
                    'agreements' => [
                        [
                            'id' => 20,
                            'files' => [
                                [
                                    'id' => 48,
                                    'link' => 'dawdaw',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T14:16:28+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T14:16:28+02:00'
                                ],
                                [
                                    'id' => 49,
                                    'link' => 'dawdaw',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T14:16:28+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T14:16:28+02:00'
                                ]
                            ],
                            'sign_date' => '2018-01-29T17:02:05+02:00',
                            'expires' => '2018-01-29T17:02:05+02:00',
                            'auto_extend' => false,
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:16:28+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:16:28+02:00'
                        ]
                    ],
                    'competitors' => [
                        [
                            'id' => 14,
                            'name' => 'Test Competitor',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:06:13+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:06:13+02:00'
                        ]
                    ],
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:15:55+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-23T15:25:23+02:00',
                    'object_type' => null
                ]
            ],
            'type' => 1,
            'id' => 'f9808785-9a36-4570-9a31-10d330b3bd05',
            'name' => '3123dadaw',
            'google_place_id' => null,
            'photos' => [
                [
                    'id' => 29,
                    'link' => 'daweawdaw',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:52+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:52+02:00'
                ],
                [
                    'id' => 30,
                    'link' => 'second',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:52+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:52+02:00'
                ]
            ],
            'housing' => [
                'id'=> 11,
                'name' => 'Test',
                'address' => 'sdiafhiuhgsdihgioushdgi',
                'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                'created_at' => '2018-02-20T12:25:01+02:00',
                'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                'updated_at' => '2018-02-20T12:25:01+02:00'
            ],
            'agreements' => [
                [
                    'id' => 16,
                    'files' => [
                        [
                            'id' => 26,
                            'link' => 'dawdaw',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:16+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:16+02:00'
                        ],
                        [
                            'id' => 27,
                            'link' => 'dawdaw',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:16+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:16+02:00'
                        ]
                    ],
                    'sign_date' => '2018-01-29T17:02:05+02:00',
                    'expires' => '2018-01-29T17:02:05+02:00',
                    'auto_extend' => false,
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:16+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:16+02:00'
                ]
            ],
            'competitors' => [
                [
                    'id' => 14,
                    'name' => 'Test Competitor',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:06:13+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:06:13+02:00'
                ]
            ],
            'created_by' => 'admin',
            'created_at' => '2018-02-20T14:47:27+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T14:47:27+02:00',
            'object_type' => null
        ];

        $client = $this->createClient($data, 200);
        $buildingsFacade = new BuildingsFacade($client, self::$baseApiUrl);
        $complex = $buildingsFacade->getComplex($token, '1');
        $this->assertComplex($data, $complex);
    }

    public function testNewComplex()
    {
        $token = 'some-token';
        $data = [
            'multi_apartment' => [
                [
                    'floors' => null,
                    'rooms' => null,
                    'sections' => [
                        [
                            'floors' => null,
                            'rooms' => null,
                            'section_number' => null,
                            'type' => 3,
                            'id' => 'b65e6647-1749-4ab8-8c0e-21909533f23c',
                            'name' => '321dwadaw',
                            'google_place_id' => null,
                            'photos' => [
                                [
                                    'id' => 53,
                                    'link' => '321dadwa',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T14:18:48+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T14:18:48+02:00'
                                ],
                                [
                                    'id' => 54,
                                    'link' => '321dadwa2',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T14:18:48+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T14:18:48+02:00'
                                ]
                            ],
                            'housing' => [
                                'id'=> 11,
                                'name' => 'Test',
                                'address' => 'sdiafhiuhgsdihgioushdgi',
                                'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                                'created_at' => '2018-02-20T12:25:01+02:00',
                                'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                                'updated_at' => '2018-02-20T12:25:01+02:00'
                            ],
                            'agreements' => [
                                [
                                    'id' => 16,
                                    'files' => [
                                        [
                                            'id' => 26,
                                            'link' => 'dawdaw',
                                            'created_by' => 'admin',
                                            'created_at' => '2018-02-20T12:34:16+02:00',
                                            'edited_by' => 'admin',
                                            'updated_at' => '2018-02-20T12:34:16+02:00'
                                        ],
                                        [
                                            'id' => 27,
                                            'link' => 'dawdaw',
                                            'created_by' => 'admin',
                                            'created_at' => '2018-02-20T12:34:16+02:00',
                                            'edited_by' => 'admin',
                                            'updated_at' => '2018-02-20T12:34:16+02:00'
                                        ]
                                    ],
                                    'sign_date' => '2018-01-29T17:02:05+02:00',
                                    'expires' => '2018-01-29T17:02:05+02:00',
                                    'auto_extend' => false,
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T12:34:16+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T12:34:16+02:00'
                                ]
                            ],
                            'competitors' => [
                                [
                                    'id' => 14,
                                    'name' => 'Test Competitor',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T14:06:13+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T14:06:13+02:00'
                                ]
                            ],
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:18:05+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:18:48+02:00',
                            'object_type' => null
                        ]
                    ],
                    'type' => 2,
                    'id' => '49c9f8be-6189-4b73-97ec-365c1f4e25dd',
                    'name' => '321dwadaw',
                    'google_place_id' => null,
                    'photos' => [
                        [
                            'id' => 51,
                            'link' => '321dadwa',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:17:46+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:17:46+02:00'
                        ],
                        [
                            'id' => 52,
                            'link' => '321dadwa2',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:17:46+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:17:46+02:00'
                        ]
                    ],
                    'housing' => [
                        'id'=> 11,
                        'name' => 'Test',
                        'address' => 'sdiafhiuhgsdihgioushdgi',
                        'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                        'created_at' => '2018-02-20T12:25:01+02:00',
                        'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                        'updated_at' => '2018-02-20T12:25:01+02:00'
                    ],
                    'agreements' => [
                        [
                            'id' => 20,
                            'files' => [
                                [
                                    'id' => 48,
                                    'link' => 'dawdaw',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T14:16:28+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T14:16:28+02:00'
                                ],
                                [
                                    'id' => 49,
                                    'link' => 'dawdaw',
                                    'created_by' => 'admin',
                                    'created_at' => '2018-02-20T14:16:28+02:00',
                                    'edited_by' => 'admin',
                                    'updated_at' => '2018-02-20T14:16:28+02:00'
                                ]
                            ],
                            'sign_date' => '2018-01-29T17:02:05+02:00',
                            'expires' => '2018-01-29T17:02:05+02:00',
                            'auto_extend' => false,
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:16:28+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:16:28+02:00'
                        ]
                    ],
                    'competitors' => [
                        [
                            'id' => 14,
                            'name' => 'Test Competitor',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T14:06:13+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T14:06:13+02:00'
                        ]
                    ],
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:15:55+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-23T15:25:23+02:00',
                    'object_type' => null
                ]
            ],
            'type' => 1,
            'id' => 'f9808785-9a36-4570-9a31-10d330b3bd05',
            'name' => '3123dadaw',
            'google_place_id' => null,
            'photos' => [
                [
                    'id' => 29,
                    'link' => 'daweawdaw',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:52+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:52+02:00'
                ],
                [
                    'id' => 30,
                    'link' => 'second',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:52+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:52+02:00'
                ]
            ],
            'housing' => [
                'id'=> 11,
                'name' => 'Test',
                'address' => 'sdiafhiuhgsdihgioushdgi',
                'created_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                'created_at' => '2018-02-20T12:25:01+02:00',
                'edited_by' => '76f80856-fc42-11e7-8450-fea9aa178066',
                'updated_at' => '2018-02-20T12:25:01+02:00'
            ],
            'agreements' => [
                [
                    'id' => 16,
                    'files' => [
                        [
                            'id' => 26,
                            'link' => 'dawdaw',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:16+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:16+02:00'
                        ],
                        [
                            'id' => 27,
                            'link' => 'dawdaw',
                            'created_by' => 'admin',
                            'created_at' => '2018-02-20T12:34:16+02:00',
                            'edited_by' => 'admin',
                            'updated_at' => '2018-02-20T12:34:16+02:00'
                        ]
                    ],
                    'sign_date' => '2018-01-29T17:02:05+02:00',
                    'expires' => '2018-01-29T17:02:05+02:00',
                    'auto_extend' => false,
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T12:34:16+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T12:34:16+02:00'
                ]
            ],
            'competitors' => [
                [
                    'id' => 14,
                    'name' => 'Test Competitor',
                    'created_by' => 'admin',
                    'created_at' => '2018-02-20T14:06:13+02:00',
                    'edited_by' => 'admin',
                    'updated_at' => '2018-02-20T14:06:13+02:00'
                ]
            ],
            'created_by' => 'admin',
            'created_at' => '2018-02-20T14:47:27+02:00',
            'edited_by' => 'admin',
            'updated_at' => '2018-02-20T14:47:27+02:00',
            'object_type' => null
        ];

        $client = $this->createClient($data, 200);

        $buildingsFacade   = new BuildingsFacade($client, self::$baseApiUrl);
        $newComplex = $buildingsFacade->newComplex($token, $data['name'], $data['google_place_id'], $data['object_type']);

        $this->assertComplex($data, $newComplex);
    }

    public function testUpdateComplex()
    {
        $token = 'test-token';
        $data  = [
            'id' => '1'
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->updateComplex($token, $data['id']);
        $this->assertTrue($updateResult);
    }

    public function testAddMultiApartmentToComplex()
    {
        $token = 'test-token';
        $data = [
            'complex_id' => '25',
            'multi_apartment_id' => '2'
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->addMultiApartmentToComplex($token, $data['complex_id'], $data['multi_apartment_id']);
        $this->assertTrue($updateResult);
    }

    public function testRemoveMultiApartmentFromComplex()
    {
        $token = 'test-token';
        $data = [
            'complex_id' => '25',
            'multi_apartment_id' => '2'
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->removeMultiApartmentFromComplex($token, $data['complex_id'], $data['multi_apartment_id']);
        $this->assertTrue($updateResult);
    }

    public function testDeleteAttachment()
    {
        $token = 'test-token';
        $data = [
            'id' => 25
        ];

        $client       = $this->createClient(null, 200);
        $buildingsFacade  = new BuildingsFacade($client, self::$baseApiUrl);
        $updateResult = $buildingsFacade->deleteAttachment($token, $data['id']);
        $this->assertTrue($updateResult);
    }

    /**
     * @param array $data
     * @param $house
     */
    private function assertHouse(array $data, $house)
    {
        $this->assertInstanceOf(House::class, $house);
        $this->assertEquals($data['name'], $house->name);
        $this->assertEquals(new \DateTimeImmutable($data['created_at']), $house->createdAt);
        $this->assertEquals($data['edited_by'], $house->editedBy);
        $this->assertEquals($data['created_by'], $house->createdBy);
        $this->assertEquals(new \DateTimeImmutable($data['updated_at']), $house->updatedAt);
        $this->assertEquals($data['floors'], $house->floors);
        $this->assertEquals($data['id'], $house->id);
        $this->assertEquals($data['google_place_id'], $house->googlePlaceId);
        $this->assertContainsOnlyInstancesOf(Agreement::class, $house->agreements);
        $this->assertContainsOnlyInstancesOf(Attachment::class, $house->photos);
        $this->assertContainsOnlyInstancesOf(Competitor::class, $house->competitors);
        $this->assertInstanceOf(Housing::class, $house->housing);
        $houseAgreement = $house->agreements[0];
        $this->assertInstanceOf(Agreement::class, $houseAgreement);
        $this->assertContainsOnlyInstancesOf(Attachment::class, $houseAgreement->files);
    }

    /**
     * @param array $data
     * @param $multiApartment
     */
    private function assertMultiApartment(array $data, $multiApartment)
    {
        $this->assertInstanceOf(MultiApartment::class, $multiApartment);
        $this->assertEquals($data['name'], $multiApartment->name);
        $this->assertEquals(new \DateTimeImmutable($data['created_at']), $multiApartment->createdAt);
        $this->assertEquals($data['edited_by'], $multiApartment->editedBy);
        $this->assertEquals($data['created_by'], $multiApartment->createdBy);
        $this->assertEquals(new \DateTimeImmutable($data['updated_at']), $multiApartment->updatedAt);
        $this->assertEquals($data['floors'], $multiApartment->floors);
        $this->assertEquals($data['rooms'], $multiApartment->rooms);
        $this->assertEquals($data['google_place_id'], $multiApartment->googlePlaceId);
        $this->assertEquals($data['type'], $multiApartment->type);
        $this->assertEquals($data['object_type'], $multiApartment->objectType);
        $this->assertContainsOnlyInstancesOf(Section::class, $multiApartment->sections);
        $this->assertContainsOnlyInstancesOf(Attachment::class, $multiApartment->photos);
        $this->assertInstanceOf(Housing::class, $multiApartment->housing);
        $this->assertContainsOnlyInstancesOf(Agreement::class, $multiApartment->agreements);
        $this->assertContainsOnlyInstancesOf(Competitor::class, $multiApartment->competitors);
        $multiApartmentSection = $multiApartment->sections[0];
        $this->assertContainsOnlyInstancesOf(Agreement::class, $multiApartmentSection->agreements);
        $this->assertContainsOnlyInstancesOf(Attachment::class, $multiApartmentSection->photos);
        $this->assertContainsOnlyInstancesOf(Competitor::class, $multiApartmentSection->competitors);
        $this->assertInstanceOf(Housing::class, $multiApartmentSection->housing);
        $multiApartmentSectionAgreement = $multiApartmentSection->agreements[0];
        $this->assertContainsOnlyInstancesOf(Attachment::class, $multiApartmentSectionAgreement->files);
    }

    /**
     * @param array $data
     * @param $complex
     */
    private function assertComplex(array $data, $complex)
    {
        $this->assertInstanceOf(Complex::class, $complex);
        $this->assertEquals($data['name'], $complex->name);
        $this->assertEquals(new \DateTimeImmutable($data['created_at']), $complex->createdAt);
        $this->assertEquals($data['edited_by'], $complex->editedBy);
        $this->assertEquals($data['created_by'], $complex->createdBy);
        $this->assertEquals(new \DateTimeImmutable($data['updated_at']), $complex->updatedAt);
        $this->assertEquals($data['google_place_id'], $complex->googlePlaceId);
        $this->assertEquals($data['type'], $complex->type);
        $this->assertEquals($data['object_type'], $complex->objectType);
        $this->assertContainsOnlyInstancesOf(Attachment::class, $complex->photos);
        $this->assertContainsOnlyInstancesOf(Agreement::class, $complex->agreements);
        $this->assertContainsOnlyInstancesOf(Competitor::class, $complex->competitors);
        $this->assertInstanceOf(Housing::class, $complex->housing);
        $this->assertContainsOnlyInstancesOf(MultiApartment::class, $complex->multiApartments);
    }

    /**
     * @param array $responseBody
     * @param int   $statusCode
     *
     * @return \GuzzleHttp\Client
     */
    private function createClient(?array $responseBody, int $statusCode): Client
    {
        $mock = new MockHandler(
            [
                new Response($statusCode, ['Content-Type' => 'application/json'],
                    $responseBody ? \json_encode($responseBody) : $responseBody),
            ]
        );

        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}