<?php
declare(strict_types=1);

namespace DpDocument\Facades\Buildings;

use DpDocument\Facades\Buildings\DTO\Agreement;
use DpDocument\Facades\Buildings\DTO\Attachment;
use DpDocument\Facades\Buildings\DTO\Building;
use DpDocument\Facades\Buildings\DTO\Competitor;
use DpDocument\Facades\Buildings\DTO\Complex;
use DpDocument\Facades\Buildings\DTO\House;
use DpDocument\Facades\Buildings\DTO\Housing;
use DpDocument\Facades\Buildings\DTO\MultiApartment;
use DpDocument\Facades\Buildings\DTO\Section;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BuildingsFacade
 *
 * @package DpDocument\Facades\Buildings
 * @since   1.3.0
 * DpDocument | Research & Development
 */
final class BuildingsFacade
{
    public const TYPE_COMPLEX = 1;
    public const TYPE_MULTI_APARTMENT = 2;
    public const TYPE_SECTION_ALONE = 3;
    public const TYPE_HOUSE = 4;
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $client;
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * BuildingsFacade constructor.
     *
     * @param ClientInterface $client
     * @param string $baseUrl
     */
    public function __construct(ClientInterface $client, string $baseUrl)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $accessToken
     *
     * @return Building[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getBuildings(
        string $accessToken
    ): array {

        try {
            $url = $this->baseUrl . '/buildings';
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $data = $this->processResult($response);

            $buildings = [];

            foreach ($data as $item) {
                switch ($item['type']) {
                    case self::TYPE_COMPLEX:
                        $buildings[] = $this->processComplex($item);
                        break;
                    case self::TYPE_MULTI_APARTMENT:
                        $buildings[] = $this->processMultiApartment($item);
                        break;
                    case self::TYPE_HOUSE:
                        $buildings[] = $this->processHouse($item);
                        break;
                }
            }

            return $buildings;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param null|string $search
     * @param int|null $page
     * @param int|null $limit
     * @param array|null $sort
     *
     * @return Competitor[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getCompetitors(
        string $accessToken,
        ?string $search = null,
        ?int $page = null,
        ?int $limit = null,
        ?array $sort = null
    ): array {
        $queryParams = [];

        if (null !== $search) {
            $queryParams['q'] = $search;
        }

        if (null !== $page) {
            $queryParams['page'] = $page;
        }

        if (null !== $limit) {
            $queryParams['limit'] = $limit;
        }

        if (null !== $sort) {
            $queryParams['sort'] = \join(',', \array_unique($sort));
        }

        try {
            $url = $this->baseUrl . '/competitors';
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'query' => $queryParams
                    ]);
            $data = $this->processResult($response);

            $competitors = [];

            foreach ($data as $item) {
                $competitors[] = Competitor::createFromResponse($item);
            }

            return $competitors;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param int $id
     *
     * @return Competitor
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getCompetitor(string $accessToken, int $id): Competitor
    {
        try {
            $url = $this->baseUrl . '/competitors/' . $id;
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $data = $this->processResult($response);

            return Competitor::createFromResponse($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $name
     *
     * @return Competitor
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newCompetitor(string $accessToken, string $name): Competitor
    {
        try {
            $url = $this->baseUrl . '/competitors';
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            'name' => $name
                        ]
                    ]);
            $data = $this->processResult($response);

            return Competitor::createFromResponse($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param int $id
     * @param null|string $name
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function updateCompetitor(string $accessToken, int $id, ?string $name = null): bool
    {
        $jsonData = [];

        if (null !== $name) {
            $jsonData['name'] = $name;
        }

        try {
            $url = $this->baseUrl . '/competitors/' . $id;
            $response = $this->client
                ->request('put',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => $jsonData
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param int $id
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteCompetitor(string $accessToken, int $id): bool
    {
        try {
            $url = $this->baseUrl . '/competitors/' . $id;
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param null|string $search
     * @param int|null $page
     * @param int|null $limit
     * @param array|null $sort
     *
     * @return Housing[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getHousings(
        string $accessToken,
        ?string $search = null,
        ?int $page = null,
        ?int $limit = null,
        ?array $sort = null
    ): array {
        $queryParams = [];

        if (null !== $search) {
            $queryParams['q'] = $search;
        }

        if (null !== $page) {
            $queryParams['page'] = $page;
        }

        if (null !== $limit) {
            $queryParams['limit'] = $limit;
        }

        if (null !== $sort) {
            $queryParams['sort'] = \join(',', \array_unique($sort));
        }

        try {
            $url = $this->baseUrl . '/housings';
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'query' => $queryParams
                    ]);
            $data = $this->processResult($response);

            $housings = [];

            foreach ($data as $item) {
                $housings[] = $this->processHousing($item);
            }

            return $housings;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param int $id
     *
     * @return Housing
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getHousing(string $accessToken, int $id): Housing
    {
        try {
            $url = $this->baseUrl . '/housings/' . $id;
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $data = $this->processResult($response);

            return $this->processHousing($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $name
     * @param string $address
     *
     * @return Housing
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newHousing(string $accessToken, string $name, string $address): Housing
    {
        try {
            $url = $this->baseUrl . '/housings';
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            'name' => $name,
                            'address' => $address
                        ]
                    ]);
            $data = $this->processResult($response);

            return $this->processHousing($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param int $id
     * @param null|string $name
     * @param null|string $address
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function updateHousing(string $accessToken, int $id, ?string $name = null, ?string $address = null): bool
    {
        $jsonData = [];

        if (null !== $name) {
            $jsonData['name'] = $name;
        }

        if (null !== $address) {
            $jsonData['address'] = $address;
        }

        try {
            $url = $this->baseUrl . '/housings/' . $id;
            $response = $this->client
                ->request('put',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => $jsonData
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param int $id
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteHousing(string $accessToken, int $id): bool
    {
        try {
            $url = $this->baseUrl . '/housings/' . $id;
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param null|string $search
     * @param int|null $page
     * @param int|null $limit
     * @param array|null $sort
     *
     * @return House[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getHouses(
        string $accessToken,
        ?string $search = null,
        ?int $page = null,
        ?int $limit = null,
        ?array $sort = null
    ): array {
        $queryParams = [];

        if (null !== $search) {
            $queryParams['q'] = $search;
        }

        if (null !== $page) {
            $queryParams['page'] = $page;
        }

        if (null !== $limit) {
            $queryParams['limit'] = $limit;
        }

        if (null !== $sort) {
            $queryParams['sort'] = \join(',', \array_unique($sort));
        }

        try {
            $url = $this->baseUrl . '/houses';
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'query' => $queryParams
                    ]);
            $data = $this->processResult($response);

            $houses = [];

            foreach ($data as $item) {
                $houses[] = $this->processHouse($item);
            }

            return $houses;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     *
     * @return House
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getHouse(string $accessToken, string $id): House
    {
        try {
            $url = $this->baseUrl . '/houses/' . $id;
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $data = $this->processResult($response);

            return $this->processHouse($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $name
     * @param null|int $floors
     * @param null|string $googlePlaceId
     * @param null|string $objectType
     *
     * @return House
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newHouse(
        string $accessToken,
        string $name,
        ?int $floors = null,
        ?string $googlePlaceId = null,
        ?string $objectType = null
    ): House {
        try {
            $url = $this->baseUrl . '/houses';
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            'name' => $name,
                            'floors' => $floors,
                            'google_place_id' => $googlePlaceId,
                            'object_type' => $objectType
                        ]
                    ]);
            $data = $this->processResult($response);

            return $this->processHouse($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param null|string $name
     * @param null|int $floors
     * @param null|string $googlePlaceId
     * @param null|string $objectType
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function updateHouse(
        string $accessToken,
        string $id,
        ?string $name = null,
        ?int $floors = null,
        ?string $googlePlaceId = null,
        ?string $objectType = null
    ): bool {
        $jsonData = [];

        if (null !== $name) {
            $jsonData['name'] = $name;
        }

        if (null !== $floors) {
            $jsonData['floors'] = $floors;
        }

        if (null !== $googlePlaceId) {
            $jsonData['google_place_id'] = $googlePlaceId;
        }

        if (null !== $objectType) {
            $jsonData['object_type'] = $objectType;
        }

        try {
            $url = $this->baseUrl . '/houses/' . $id;
            $response = $this->client
                ->request('put',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => $jsonData
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteHouse(string $accessToken, string $id): bool
    {
        try {
            $url = $this->baseUrl . '/houses/' . $id;
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $houseId
     * @param int $competitorId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function addCompetitorToHouse(string $accessToken, string $houseId, int $competitorId): bool
    {
        try {
            $url = $this->baseUrl . '/houses/' . $houseId . '/competitors/' . $competitorId;
            return $this->addCompetitorToBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $houseId
     * @param int $competitorId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function removeCompetitorFromHouse(string $accessToken, string $houseId, int $competitorId): bool
    {
        try {
            $url = $this->baseUrl . '/houses/' . $houseId . '/competitors/' . $competitorId;
            return $this->removeCompetitorFromBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param bool $autoExtend
     * @param \DateTimeImmutable $expires
     * @param \DateTimeImmutable $signDate
     * @param array|null $attachments
     *
     * @return Agreement
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newAgreementInHouse(
        string $accessToken,
        string $id,
        bool $autoExtend,
        \DateTimeImmutable $expires,
        \DateTimeImmutable $signDate,
        ?array $attachments = null
    ): Agreement {
        try {
            $url = $this->baseUrl . '/houses/' . $id . '/agreements';
            $jsonData = [
                'auto_extend' => $autoExtend,
                'expires' => isset($expires) ? $expires->format('Y-m-d H:i:s') : null,
                'sign_date' => isset($signDate) ? $signDate->format('Y-m-d H:i:s') : null,
                'attachments' => $attachments
            ];
            return $this->newAgreementInBuilding($accessToken, $url, $jsonData);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $houseId
     * @param int $agreementId
     * @param \DateTimeImmutable|null $signDate
     * @param \DateTimeImmutable|null $expires
     * @param bool|null $autoExtend
     * @param array|null $attachments
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function updateAgreementInHouse(
        string $accessToken,
        string $houseId,
        int $agreementId,
        ?bool $autoExtend = null,
        ?\DateTimeImmutable $signDate = null,
        ?\DateTimeImmutable $expires = null,
        ?array $attachments = null
    ): bool {
        $jsonData = [];

        if (null !== $signDate) {
            $jsonData['sign_date'] = $signDate->format('Y-m-d H:i:s');
        }

        if (null !== $expires) {
            $jsonData['expires'] = $expires->format('Y-m-d H:i:s');
        }

        if (null !== $autoExtend) {
            $jsonData['auto_extend'] = $autoExtend;
        }

        if (null !== $attachments) {
            $jsonData['attachments'] = $attachments;
        }

        try {
            $url = $this->baseUrl . '/houses/' . $houseId . '/agreements/' . $agreementId;
            return $this->updateAgreementInBuilding($accessToken, $url, $jsonData);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $houseId
     * @param int $agreementId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteAgreementInHouse(string $accessToken, string $houseId, int $agreementId): bool
    {
        try {
            $url = $this->baseUrl . '/houses/' . $houseId . '/agreements/' . $agreementId;
            return $this->deleteAgreementInBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $houseId
     * @param int $housingId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function addHousingToHouse(string $accessToken, string $houseId, int $housingId): bool
    {
        try {
            $url = $this->baseUrl . '/houses/' . $houseId . '/housings/' . $housingId;
            return $this->addHousingToBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $houseId
     * @param int $housingId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function removeHousingFromHouse(string $accessToken, string $houseId, int $housingId): bool
    {
        try {
            $url = $this->baseUrl . '/houses/' . $houseId . '/housings/' . $housingId;
            return $this->removeHousingFromBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param array $attachments
     *
     * @return Attachment[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newAttachmentInHouse(string $accessToken, string $id, array $attachments): array
    {
        try {
            $url = $this->baseUrl . '/houses/' . $id . '/attachments';
            return $this->newAttachmentInBuilding($accessToken, $url, $attachments);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $houseId
     * @param int $attachmentId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteAttachmentInHouse(string $accessToken, string $houseId, int $attachmentId): bool
    {
        try {
            $url = $this->baseUrl . '/houses/' . $houseId . '/attachments/' . $attachmentId;
            return $this->deleteAttachmentInBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param null|string $search
     * @param int|null $page
     * @param int|null $limit
     * @param array|null $sort
     *
     * @return MultiApartment[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getMultiApartments(
        string $accessToken,
        ?string $search = null,
        ?int $page = null,
        ?int $limit = null,
        ?array $sort = null
    ): array {
        $queryParams = [];

        if (null !== $search) {
            $queryParams['q'] = $search;
        }

        if (null !== $page) {
            $queryParams['page'] = $page;
        }

        if (null !== $limit) {
            $queryParams['limit'] = $limit;
        }

        if (null !== $sort) {
            $queryParams['sort'] = \join(',', \array_unique($sort));
        }

        try {
            $url = $this->baseUrl . '/multi-apartments';
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'query' => $queryParams
                    ]);
            $data = $this->processResult($response);

            $multiApartments = [];

            foreach ($data as $item) {
                $multiApartments[] = $this->processMultiApartment($item);
            }

            return $multiApartments;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     *
     * @return MultiApartment
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getMultiApartment(string $accessToken, string $id): MultiApartment
    {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $id;
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $data = $this->processResult($response);

            return $this->processMultiApartment($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $name
     * @param int|null $floors
     * @param int|null $rooms
     * @param null|string $googlePlaceId
     * @param null|string $objectType
     *
     * @return MultiApartment
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newMultiApartment(
        string $accessToken,
        string $name,
        ?int $floors = null,
        ?int $rooms = null,
        ?string $googlePlaceId = null,
        ?string $objectType = null
    ): MultiApartment {
        try {
            $url = $this->baseUrl . '/multi-apartments';
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            'name' => $name,
                            'floors' => $floors,
                            'rooms' => $rooms,
                            'google_place_id' => $googlePlaceId,
                            'object_type' => $objectType
                        ]
                    ]);
            $data = $this->processResult($response);

            return $this->processMultiApartment($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param null|string $name
     * @param int|null $floors
     * @param int|null $rooms
     * @param null|string $googlePlaceId
     * @param null|string $objectType
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function updateMultiApartment(
        string $accessToken,
        string $id,
        ?string $name = null,
        ?int $floors = null,
        ?int $rooms = null,
        ?string $googlePlaceId = null,
        ?string $objectType = null
    ): bool {
        $jsonData = [];

        if (null !== $name) {
            $jsonData['name'] = $name;
        }

        if (null !== $googlePlaceId) {
            $jsonData['google_place_id'] = $googlePlaceId;
        }

        if (null !== $floors) {
            $jsonData['floors'] = $floors;
        }

        if (null !== $rooms) {
            $jsonData['rooms'] = $rooms;
        }

        if (null !== $objectType) {
            $jsonData['object_type'] = $objectType;
        }

        try {
            $url = $this->baseUrl . '/multi-apartments/' . $id;
            $response = $this->client
                ->request('put',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => $jsonData
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteMultiApartment(string $accessToken, string $id): bool
    {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $id;
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param int $competitorId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function addCompetitorToMultiApartment(string $accessToken, string $multiApartmentId, int $competitorId): bool
    {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/competitors/' . $competitorId;
            return $this->addCompetitorToBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param int $competitorId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function removeCompetitorFromMultiApartment(string $accessToken, string $multiApartmentId, int $competitorId): bool
    {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/competitors/' . $competitorId;
            return $this->removeCompetitorFromBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param bool $autoExtend
     * @param \DateTimeImmutable $expires
     * @param \DateTimeImmutable $signDate
     * @param array|null $attachments
     *
     * @return Agreement
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newAgreementInMultiApartment(
        string $accessToken,
        string $id,
        bool $autoExtend,
        \DateTimeImmutable $expires,
        \DateTimeImmutable $signDate,
        ?array $attachments = null
    ): Agreement {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $id . '/agreements';
            $jsonData = [
                'auto_extend' => $autoExtend,
                'expires' => isset($expires) ? $expires->format('Y-m-d H:i:s') : null,
                'sign_date' => isset($signDate) ? $signDate->format('Y-m-d H:i:s') : null,
                'attachments' => $attachments
            ];
            return $this->newAgreementInBuilding($accessToken, $url, $jsonData);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param int $agreementId
     * @param \DateTimeImmutable|null $signDate
     * @param \DateTimeImmutable|null $expires
     * @param bool|null $autoExtend
     * @param array|null $attachments
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function updateAgreementInMultiApartment(
        string $accessToken,
        string $multiApartmentId,
        int $agreementId,
        ?bool $autoExtend = null,
        ?\DateTimeImmutable $signDate = null,
        ?\DateTimeImmutable $expires = null,
        ?array $attachments = null
    ): bool {
        $jsonData = [];

        if (null !== $signDate) {
            $jsonData['sign_date'] = $signDate->format('Y-m-d H:i:s');
        }

        if (null !== $expires) {
            $jsonData['expires'] = $expires->format('Y-m-d H:i:s');
        }

        if (null !== $autoExtend) {
            $jsonData['auto_extend'] = $autoExtend;
        }

        if (null !== $attachments) {
            $jsonData['attachments'] = $attachments;
        }

        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/agreements/' . $agreementId;
            return $this->updateAgreementInBuilding($accessToken, $url, $jsonData);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param int $agreementId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteAgreementInMultiApartment(string $accessToken, string $multiApartmentId, int $agreementId): bool
    {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/agreements/' . $agreementId;
            return $this->deleteAgreementInBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param int $housingId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function addHousingToMultiApartment(string $accessToken, string $multiApartmentId, int $housingId): bool
    {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/housings/' . $housingId;
            return $this->addHousingToBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param int $housingId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function removeHousingFromMultiApartment(string $accessToken, string $multiApartmentId, int $housingId): bool
    {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/housings/' . $housingId;
            return $this->removeHousingFromBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param array $attachments
     *
     * @return Attachment[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newAttachmentInMultiApartment(string $accessToken, string $id, array $attachments): array
    {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $id . '/attachments';
            return $this->newAttachmentInBuilding($accessToken, $url, $attachments);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param int $attachmentId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteAttachmentInMultiApartment(string $accessToken, string $multiApartmentId, int $attachmentId): bool
    {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/attachments/' . $attachmentId;
            return $this->deleteAttachmentInBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param string $name
     * @param null|string $sectionNumber
     * @param int|null $floors
     * @param int|null $rooms
     * @param null|string $googlePlaceId
     * @param null|string $objectType
     *
     * @return MultiApartment
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newSectionInMultiApartment(
        string $accessToken,
        string $id,
        string $name,
        ?string $sectionNumber = null,
        ?int $floors = null,
        ?int $rooms = null,
        ?string $googlePlaceId = null,
        ?string $objectType = null
    ): MultiApartment {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $id . '/sections';
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            'name' => $name,
                            'section_number' => $sectionNumber,
                            'floors' => $floors,
                            'rooms' => $rooms,
                            'google_place_id' => $googlePlaceId,
                            'object_type' => $objectType
                        ]
                    ]);
            $data = $this->processResult($response);

            return $this->processMultiApartment($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param string $sectionId
     * @param null|string $name
     * @param null|string $googlePlaceId
     * @param int|null $floors
     * @param null|string $sectionNumber
     * @param int|null $rooms
     * @param null|string $objectType
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function updateSectionInMultiApartment(
        string $accessToken,
        string $multiApartmentId,
        string $sectionId,
        ?string $name = null,
        ?string $sectionNumber = null,
        ?int $floors = null,
        ?int $rooms = null,
        ?string $googlePlaceId = null,
        ?string $objectType = null
    ): bool {
        $jsonData = [];

        if (null !== $name) {
            $jsonData['name'] = $name;
        }

        if (null !== $googlePlaceId) {
            $jsonData['google_place_id'] = $googlePlaceId;
        }

        if (null !== $floors) {
            $jsonData['floors'] = $floors;
        }

        if (null !== $sectionNumber) {
            $jsonData['section_number'] = $sectionNumber;
        }

        if (null !== $rooms) {
            $jsonData['rooms'] = $rooms;
        }

        if (null !== $objectType) {
            $jsonData['object_type'] = $objectType;
        }

        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/sections/' . $sectionId;
            $response = $this->client
                ->request('put',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => $jsonData
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param string $sectionId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteSectionInMultiApartment(string $accessToken, string $multiApartmentId, string $sectionId): bool
    {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/sections/' . $sectionId;
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param string $sectionId
     * @param array $attachments
     *
     * @return Attachment[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newAttachmentInSectionInMultiApartment(
        string $accessToken,
        string $multiApartmentId,
        string $sectionId,
        array $attachments
    ): array {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/sections/' . $sectionId . '/attachments';
            return $this->newAttachmentInBuilding($accessToken, $url, $attachments);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $multiApartmentId
     * @param string $sectionId
     * @param int $attachmentId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteAttachmentInSectionInMultiApartment(
        string $accessToken,
        string $multiApartmentId,
        string $sectionId,
        int $attachmentId
    ): bool {
        try {
            $url = $this->baseUrl . '/multi-apartments/' . $multiApartmentId . '/sections/' . $sectionId . '/attachments/' . $attachmentId;
            return $this->deleteAttachmentInBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param null|string $search
     * @param int|null $page
     * @param int|null $limit
     * @param array|null $sort
     *
     * @return Complex[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getComplexes(
        string $accessToken,
        ?string $search = null,
        ?int $page = null,
        ?int $limit = null,
        ?array $sort = null
    ): array {
        $queryParams = [];

        if (null !== $search) {
            $queryParams['q'] = $search;
        }

        if (null !== $page) {
            $queryParams['page'] = $page;
        }

        if (null !== $limit) {
            $queryParams['limit'] = $limit;
        }

        if (null !== $sort) {
            $queryParams['sort'] = \join(',', \array_unique($sort));
        }

        try {
            $url = $this->baseUrl . '/complexes';
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'query' => $queryParams
                    ]);
            $data = $this->processResult($response);

            $complexes = [];

            foreach ($data as $item) {
                $complexes[] = $this->processComplex($item);
            }

            return $complexes;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     *
     * @return Complex
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function getComplex(string $accessToken, string $id): Complex
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $id;
            $response = $this->client
                ->request('get',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $data = $this->processResult($response);

            return $this->processComplex($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $name
     * @param null|string $googlePlaceId
     * @param null|string $objectType
     *
     * @return Complex
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newComplex(
        string $accessToken,
        string $name,
        ?string $googlePlaceId = null,
        ?string $objectType = null
    ): Complex {
        try {
            $url = $this->baseUrl . '/complexes';
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            'name' => $name,
                            'google_place_id' => $googlePlaceId,
                            'object_type' => $objectType
                        ]
                    ]);
            $data = $this->processResult($response);

            return $this->processComplex($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param null|string $name
     * @param null|string $googlePlaceId
     * @param null|string $objectType
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function updateComplex(
        string $accessToken,
        string $id,
        ?string $name = null,
        ?string $googlePlaceId = null,
        ?string $objectType = null
    ): bool {
        $jsonData = [];

        if (null !== $name) {
            $jsonData['name'] = $name;
        }

        if (null !== $googlePlaceId) {
            $jsonData['google_place_id'] = $googlePlaceId;
        }

        if (null !== $objectType) {
            $jsonData['object_type'] = $objectType;
        }

        try {
            $url = $this->baseUrl . '/complexes/' . $id;
            $response = $this->client
                ->request('put',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => $jsonData
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param bool $withMultiApartment
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteComplex(string $accessToken, string $id, bool $withMultiApartment): bool
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $id;
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            'with_multi_apartment' => $withMultiApartment
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $complexId
     * @param int $competitorId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function addCompetitorToComplex(string $accessToken, string $complexId, int $competitorId): bool
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $complexId . '/competitors/' . $competitorId;
            return $this->addCompetitorToBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $complexId
     * @param int $competitorId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function removeCompetitorFromComplex(string $accessToken, string $complexId, int $competitorId): bool
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $complexId . '/competitors/' . $competitorId;
            return $this->removeCompetitorFromBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param bool $autoExtend
     * @param \DateTimeImmutable $expires
     * @param \DateTimeImmutable $signDate
     * @param array|null $attachments
     *
     * @return Agreement
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newAgreementInComplex(
        string $accessToken,
        string $id,
        bool $autoExtend,
        \DateTimeImmutable $expires,
        \DateTimeImmutable $signDate,
        ?array $attachments = null
    ): Agreement {
        try {
            $url = $this->baseUrl . '/complexes/' . $id . '/agreements';
            $jsonData = [
                'auto_extend' => $autoExtend,
                'expires' => isset($expires) ? $expires->format('Y-m-d H:i:s') : null,
                'sign_date' => isset($signDate) ? $signDate->format('Y-m-d H:i:s') : null,
                'attachments' => $attachments
            ];
            return $this->newAgreementInBuilding($accessToken, $url, $jsonData);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $complexId
     * @param int $agreementId
     * @param bool|null $autoExtend
     * @param \DateTimeImmutable|null $expires
     * @param \DateTimeImmutable|null $signDate
     * @param array|null $attachments
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function updateAgreementInComplex(
        string $accessToken,
        string $complexId,
        int $agreementId,
        ?bool $autoExtend = null,
        ?\DateTimeImmutable $expires = null,
        ?\DateTimeImmutable $signDate = null,
        ?array $attachments = null
    ): bool {
        $jsonData = [];

        if (null !== $autoExtend) {
            $jsonData['auto_extend'] = $autoExtend;
        }

        if (null !== $expires) {
            $jsonData['expires'] = $expires->format('Y-m-d H:i:s');
        }

        if (null !== $signDate) {
            $jsonData['sign_date'] = $signDate->format('Y-m-d H:i:s');
        }

        if (null !== $attachments) {
            $jsonData['attachments'] = $attachments;
        }

        try {
            $url = $this->baseUrl . '/complexes/' . $complexId . '/agreements/' . $agreementId;
            return $this->updateAgreementInBuilding($accessToken, $url, $jsonData);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $complexId
     * @param int $agreementId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteAgreementInComplex(string $accessToken, string $complexId, int $agreementId): bool
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $complexId . '/agreements/' . $agreementId;
            return $this->deleteAgreementInBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $complexId
     * @param int $housingId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function addHousingToComplex(string $accessToken, string $complexId, int $housingId): bool
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $complexId . '/housings/' . $housingId;
            return $this->addHousingToBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $complexId
     * @param int $housingId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function removeHousingFromComplex(string $accessToken, string $complexId, int $housingId): bool
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $complexId . '/housings/' . $housingId;
            return $this->removeHousingFromBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $id
     * @param array $attachments
     *
     * @return Attachment[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function newAttachmentInComplex(string $accessToken, string $id, array $attachments): array
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $id . '/attachments';
            return $this->newAttachmentInBuilding($accessToken, $url, $attachments);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $complexId
     * @param int $attachmentId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteAttachmentInComplex(string $accessToken, string $complexId, int $attachmentId): bool
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $complexId . '/attachments/' . $attachmentId;
            return $this->deleteAttachmentInBuilding($accessToken, $url);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $complexId
     * @param string $multiApartmentId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function addMultiApartmentToComplex(string $accessToken, string $complexId, string $multiApartmentId): bool
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $complexId . '/multi-apartments/' . $multiApartmentId;
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $complexId
     * @param string $multiApartmentId
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function removeMultiApartmentFromComplex(string $accessToken, string $complexId, string $multiApartmentId): bool
    {
        try {
            $url = $this->baseUrl . '/complexes/' . $complexId . '/multi-apartments/' . $multiApartmentId;
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param int $id
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    public function deleteAttachment(string $accessToken, int $id): bool
    {
        try {
            $url = $this->baseUrl . '/attachments/' . $id;
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return array|null
     * @throws \DpDocument\Facades\Buildings\BuildingsException
     */
    private function processResult(ResponseInterface $response): ?array
    {
        if (in_array($response->getStatusCode(), [200, 204])) {
            return $this->parseBody($response->getBody()->getContents());
        } else {
            $error = $this->parseBody($response->getBody()->getContents());

            if (isset($error['error'])) {
                throw new BuildingsException($error['error']);
            } elseif (isset($error['critical'])) {
                throw new BuildingsException($error['critical']);
            } else {
                throw new BuildingsException('Internal server error');
            }
        }
    }

    /**
     * @param string $json
     *
     * @return array|null
     */
    private function parseBody(string $json): ?array
    {
        $data = \json_decode($json, true);

        if (JSON_ERROR_NONE !== \json_last_error() || !is_array($data)) {
            return null;
        }

        return $data;
    }

    /**
     * @param array $house
     *
     * @return House
     * @since   1.3.0
     */
    private function processHouse(array $house): House
    {
        if (!empty($house['photos'])) {
            $attachments = [];

            foreach ($house['photos'] as $attachment) {
                $attachments[] = $this->processAttachment($attachment);
            }

            $house['photos'] = $attachments;
        }

        if (!empty($house['housing'])) {
            $house['housing'] = $this->processHousing($house['housing']);
        }

        if (!empty($house['agreements'])) {
            $agreements = [];

            foreach ($house['agreements'] as $agreement) {
                $agreements[] = $this->processAgreement($agreement);
            }

            $house['agreements'] = $agreements;
        }

        if (!empty($house['competitors'])) {
            $competitors = [];

            foreach ($house['competitors'] as $competitor) {
                $competitors[] = Competitor::createFromResponse($competitor);
            }

            $house['competitors'] = $competitors;
        }

        return House::createFromResponse($house);
    }

    /**
     * @param array $complex
     *
     * @return Complex
     * @since   1.3.0
     */
    private function processComplex(array $complex): Complex
    {
        if (!empty($complex['multi_apartment'])) {
            $multiApartments = [];

            foreach ($complex['multi_apartment'] as $multiApartment) {
                $multiApartments[] = $this->processMultiApartment($multiApartment);
            }

            $complex['multi_apartment'] = $multiApartments;
        }

        if (!empty($complex['photos'])) {
            $attachments = [];

            foreach ($complex['photos'] as $attachment) {
                $attachments[] = $this->processAttachment($attachment);
            }

            $complex['photos'] = $attachments;
        }

        if (!empty($complex['housing'])) {
            $complex['housing'] = $this->processHousing($complex['housing']);
        }

        if (!empty($complex['agreements'])) {
            $agreements = [];

            foreach ($complex['agreements'] as $agreement) {
                $agreements[] = $this->processAgreement($agreement);
            }

            $complex['agreements'] = $agreements;
        }

        if (!empty($complex['competitors'])) {
            $competitors = [];

            foreach ($complex['competitors'] as $competitor) {
                $competitors[] = Competitor::createFromResponse($competitor);
            }

            $complex['competitors'] = $competitors;
        }

        return Complex::createFromResponse($complex);
    }

    /**
     * @param array $section
     *
     * @return Section
     * @since   1.3.0
     */
    private function processSection(array $section): Section
    {
        if (!empty($section['photos'])) {
            $attachments = [];

            foreach ($section['photos'] as $attachment) {
                $attachments[] = $this->processAttachment($attachment);
            }

            $section['photos'] = $attachments;
        }

        if (!empty($section['housing'])) {
            $section['housing'] = $this->processHousing($section['housing']);
        }

        if (!empty($section['agreements'])) {
            $agreements = [];

            foreach ($section['agreements'] as $agreement) {
                $agreements[] = $this->processAgreement($agreement);
            }

            $section['agreements'] = $agreements;
        }

        if (!empty($section['competitors'])) {
            $competitors = [];

            foreach ($section['competitors'] as $competitor) {
                $competitors[] = Competitor::createFromResponse($competitor);
            }

            $section['competitors'] = $competitors;
        }

        return Section::createFromResponse($section);
    }

    /**
     * @param array $multiApartment
     *
     * @return MultiApartment
     * @since   1.3.0
     */
    private function processMultiApartment(array $multiApartment): MultiApartment
    {
        if (!empty($multiApartment['photos'])) {
            $attachments = [];

            foreach ($multiApartment['photos'] as $attachment) {
                $attachments[] = $this->processAttachment($attachment);
            }

            $multiApartment['photos'] = $attachments;
        }

        if (!empty($multiApartment['sections'])) {
            $sections = [];

            foreach ($multiApartment['sections'] as $section) {
                $sections[] = $this->processSection($section);
            }

            $multiApartment['sections'] = $sections;
        }

        if (!empty($multiApartment['housing'])) {
            $multiApartment['housing'] = $this->processHousing($multiApartment['housing']);
        }

        if (!empty($multiApartment['agreements'])) {
            $agreements = [];

            foreach ($multiApartment['agreements'] as $agreement) {
                $agreements[] = $this->processAgreement($agreement);
            }

            $multiApartment['agreements'] = $agreements;
        }

        if (!empty($multiApartment['competitors'])) {
            $competitors = [];

            foreach ($multiApartment['competitors'] as $competitor) {
                $competitors[] = Competitor::createFromResponse($competitor);
            }

            $multiApartment['competitors'] = $competitors;
        }

        return MultiApartment::createFromResponse($multiApartment);
    }

    /**
     * @param array $agreement
     *
     * @return Agreement
     * @since   1.3.0
     */
    private function processAgreement(array $agreement): Agreement
    {
        if (!empty($agreement['files'])) {
            $attachments = [];

            foreach ($agreement['files'] as $attachment) {
                $attachments[] = $this->processAttachment($attachment);
            }

            $agreement['files'] = $attachments;
        }

        return Agreement::createFromResponse($agreement);
    }

    /**
     * @param array $attachment
     *
     * @return Attachment
     * @since   1.3.0
     */
    private function processAttachment(array $attachment): Attachment
    {
        return Attachment::createFromResponse($attachment);
    }

    /**
     * @param array $housing
     *
     * @return Housing
     * @since   1.3.0
     */
    private function processHousing(array $housing): Housing
    {
        if (!empty($housing['buildings'])) {
            $buildings = [];

            foreach ($housing['buildings'] as $building) {
                switch ($building['type']) {
                    case self::TYPE_COMPLEX:
                        $buildings[] = $this->processComplex($building);
                        break;
                    case self::TYPE_MULTI_APARTMENT:
                        $buildings[] = $this->processMultiApartment($building);
                        break;
                    case self::TYPE_HOUSE:
                        $buildings[] = $this->processHouse($building);
                        break;
                }
            }

            $housing['buildings'] = $buildings;
        }

        return Housing::createFromResponse($housing);
    }

    /**
     * @param string $accessToken
     * @param string $url
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    private function addCompetitorToBuilding(string $accessToken, string $url): bool
    {
        try {
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $url
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    private function removeCompetitorFromBuilding(string $accessToken, string $url): bool
    {
        try {
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $url
     * @param array $jsonData
     *
     * @return Agreement
     * @throws BuildingsException
     * @since   1.3.0
     */
    private function newAgreementInBuilding(string $accessToken, string $url, array $jsonData): Agreement
    {
        try {
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => $jsonData
                    ]);
            $data = $this->processResult($response);

            return $this->processAgreement($data);
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $url
     * @param array $jsonData
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    private function updateAgreementInBuilding(string $accessToken, string $url, array $jsonData): bool
    {
        try {
            $response = $this->client
                ->request('put',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => $jsonData
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $url
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    private function deleteAgreementInBuilding(string $accessToken, string $url): bool
    {
        try {
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $url
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    private function addHousingToBuilding(string $accessToken, string $url): bool
    {
        try {
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $url
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    private function removeHousingFromBuilding(string $accessToken, string $url): bool
    {
        try {
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $url
     * @param array $attachments
     *
     * @return Attachment[]|[]
     * @throws BuildingsException
     * @since   1.3.0
     */
    private function newAttachmentInBuilding(string $accessToken, string $url, array $attachments): array
    {
        try {
            $response = $this->client
                ->request('post',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => $attachments
                    ]);
            $data = $this->processResult($response);

            $attachments = [];

            foreach ($data as $item) {
                $attachments[] = $this->processAttachment($item);
            }

            return $attachments;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }

    /**
     * @param string $accessToken
     * @param string $url
     *
     * @return bool
     * @throws BuildingsException
     * @since   1.3.0
     */
    private function deleteAttachmentInBuilding(string $accessToken, string $url): bool
    {
        try {
            $response = $this->client
                ->request('delete',
                    $url,
                    [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);
            $this->processResult($response);

            return true;
        } catch (\Throwable $throwable) {
            throw new BuildingsException($throwable->getMessage(), $throwable->getCode());
        }
    }
}