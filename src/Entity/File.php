<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\EntityInterface;
use App\Repository\FileRepository;
use App\Serializer\Attribute\Callback;
use App\Serializer\Callback\UrlCallback;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: FileRepository::class)]
#[ORM\Table(name: 'files')]
class File implements EntityInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    protected int $id;

    #[ORM\Column(type: "string")]
    #[SerializedName('name')]
    #[Groups(['files'])]
    protected string $originalFileName;

    #[ORM\Column(type: "uuid")]
    protected Uuid $uuid;

    #[ORM\Column(type: "string")]
    protected string $extension;

    #[ORM\Column(type: "string")]
    protected string $size;

    #[ORM\Column(type: "string", nullable: true)]
    protected ?string $mimeType = null;

    #[ORM\Column(name:"`order`", type: "integer", nullable: true, options: ['unsigned' => true])]
    protected ?int $order = null;

    #[ORM\Column(type: "boolean", options: ['default' => false])]
    protected bool $isUsed;

    #[ORM\ManyToOne(inversedBy: 'files')]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: false)]
    private ?Process $process = null;

    #[Groups('files')]
    #[Callback(class: UrlCallback::class, context: ['groups' => ['files']])]
    #[Context(context: ['url' => ['route' => 'api.files.get.by.uuid', 'parameters' => ['uuid' => 'getUuid']]])]
    protected ?string $href = null;

    public function __construct()
    {
        $this->uuid = UuidV4::v4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalFileName(): ?string
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName(string $originalFileName): self
    {
        $this->originalFileName = $originalFileName;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function gerOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(?int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function isUsed(): bool
    {
        return $this->isUsed;
    }

    public function setUsed(bool $isUsed): void
    {
        $this->isUsed = $isUsed;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function getIsUsed(): ?bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(bool $isUsed): self
    {
        $this->isUsed = $isUsed;

        return $this;
    }

    public function isIsUsed(): ?bool
    {
        return $this->isUsed;
    }

    public function getProcess(): ?Process
    {
        return $this->process;
    }

    public function setProcess(?Process $process): static
    {
        $this->process = $process;

        return $this;
    }

    public function getUuid(): string
    {
        return $this->uuid->toRfc4122();
    }

    public function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function setHref(?string $href): void
    {
        $this->href = $href;
    }
}
