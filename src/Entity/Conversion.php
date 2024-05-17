<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ConversionRepository;
use App\Repository\EntityInterface;
use App\Service\Conversion\Enum\ConversionStatusEnum;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: ConversionRepository::class)]
#[ORM\Table(name: 'conversions')]
class Conversion implements EntityInterface
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", options: ['unsigned' => true])]
    protected int $id;

    #[ORM\Column(type: "uuid")]
    protected Uuid $uuid;

    #[ORM\Column(type: "string")]
    protected string $extension;

    // todo change db to string enum
    #[ORM\Column(type: "string")]
    protected string $status = ConversionStatusEnum::STATUS_CREATED->value;

    #[ORM\Column(type: "datetimetz", nullable: true)]
    protected ?DateTime $dateConverted = null;

    #[ORM\OneToMany(mappedBy: 'conversion', targetEntity: File::class, cascade: ["persist", "remove"])]
    private Collection $files;

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

    public function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
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
            $file->setConversion($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getConversion() === $this) {
                $file->setConversion(null);
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

    public function getDateConverted(): ?DateTime
    {
        return $this->dateConverted;
    }

    public function setDateConverted(?DateTime $dateConverted): void
    {
        $this->dateConverted = $dateConverted;
    }
}
