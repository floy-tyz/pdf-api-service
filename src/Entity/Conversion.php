<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ConversionRepository;
use App\Repository\EntityInterface;
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
}
