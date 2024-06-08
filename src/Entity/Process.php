<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProcessRepository;
use App\Repository\EntityInterface;
use App\Service\Process\Enum\ProcessStatusEnum;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: ProcessRepository::class)]
#[ORM\Table(name: 'processes')]
class Process implements EntityInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", options: ['unsigned' => true])]
    protected int $id;

    #[ORM\Column(name: "`key`", type: "string")]
    protected string $key;

    #[ORM\Column(type: "uuid")]
    protected Uuid $uuid;

    #[ORM\Column(type: "string")]
    protected string $extension;

    // todo change db to string enum
    #[ORM\Column(type: "string")]
    protected string $status = ProcessStatusEnum::STATUS_CREATED->value;

    #[ORM\Column(type: "datetimetz", nullable: true)]
    protected ?DateTime $dateProcessed = null;

    #[ORM\OneToMany(mappedBy: 'process', targetEntity: File::class, cascade: ["persist", "remove"])]
    private Collection $files;

    #[ORM\Column(type: "json")]
    private array $context = [];

    public function __construct()
    {
        $this->files = new ArrayCollection();
        $this->uuid = new UuidV4();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid->toRfc4122();
    }

    /**
     * @return Collection<int, File>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setProcess($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getProcess() === $this) {
                $file->setProcess(null);
            }
        }

        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getDateProcessed(): ?DateTime
    {
        return $this->dateProcessed;
    }

    public function setDateProcessed(?DateTime $dateProcessed): void
    {
        $this->dateProcessed = $dateProcessed;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function setContext(array $context): void
    {
        $this->context = $context;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }
}
