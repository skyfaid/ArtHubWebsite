<?php


namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * Utilisateurs
 *
 * @ORM\Table(name="utilisateurs", uniqueConstraints={@ORM\UniqueConstraint(name="email", columns={"email"}), @ORM\UniqueConstraint(name="pseudo", columns={"pseudo"})})
 * @ORM\Entity
 */
class Utilisateurs
{
    /**
     * @var int
     *
     * @ORM\Column(name="utilisateur_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $utilisateur_id;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=50, nullable=false)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mot_de_passe_hash", type="string", length=255, nullable=false)
     */
    private $motDePasseHash;

    /**
     * @var string|null
     *
     * @ORM\Column(name="role", type="string", length=50, nullable=true, options={"default"="user"})
     */
    private $role = 'user';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscription", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateInscription = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="derniere_connexion", type="datetime", nullable=true)
     */
    private $derniereConnexion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url_image_profil", type="string", length=255, nullable=true)
     */
    private $urlImageProfil;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="estActif", type="boolean", nullable=true)
     */
    private $estactif = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="gender", type="string", length=20, nullable=true)
     */
    private $gender;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reset_code", type="string", length=255, nullable=true)
     */
    private $resetCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=20, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="facial_data_hash", type="string", length=255, nullable=true)
     */
     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Solution", mappedBy="utilisateur")
     */
    private $solutions;
    
    public function __construct() {
        $this->solutions = new ArrayCollection();
    }

     public function addSolution(Solution $solution): self {
        if (!$this->solutions->contains($solution)) {
            $this->solutions[] = $solution;
            $solution->setUtilisateur($this);
        }
        return $this;
    }

    private $facialDataHash;
    public function removeSolution(Solution $solution): self {
        if ($this->solutions->removeElement($solution)) {
            // Set the owning side to null (unless already changed)
            if ($solution->getUtilisateur() === $this) {
                $solution->setUtilisateur(null);
            }
        }
        return $this;
    }

    public function getSolutions(): Collection {
        return $this->solutions;
    }

    public function getUtilisateurId(): ?int
    {
        return $this->utilisateur_id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getMotDePasseHash(): ?string
    {
        return $this->motDePasseHash;
    }

    public function setMotDePasseHash(string $motDePasseHash): self
    {
        $this->motDePasseHash = $motDePasseHash;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): self
    {
        $this->dateInscription = $dateInscription;
        return $this;
    }

    public function getDerniereConnexion(): ?\DateTimeInterface
    {
        return $this->derniereConnexion;
    }

    public function setDerniereConnexion(?\DateTimeInterface $derniereConnexion): self
    {
        $this->derniereConnexion = $derniereConnexion;
        return $this;
    }

    public function getUrlImageProfil(): ?string
    {
        return $this->urlImageProfil;
    }

    public function setUrlImageProfil(?string $urlImageProfil): self
    {
        $this->urlImageProfil = $urlImageProfil;
        return $this;
    }

    public function getEstActif(): ?bool
    {
        return $this->estactif;
    }

    public function setEstActif(?bool $estactif): self
    {
        $this->estactif = $estactif;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getResetCode(): ?string
    {
        return $this->resetCode;
    }

    public function setResetCode(?string $resetCode): self
    {
        $this->resetCode = $resetCode;
        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getFacialDataHash(): ?string
    {
        return $this->facialDataHash;
    }

    public function setFacialDataHash(?string $facialDataHash): self
    {
        $this->facialDataHash = $facialDataHash;
        return $this;
    }
}
