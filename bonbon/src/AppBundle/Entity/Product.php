<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     *
     * @Assert\NotBlank()
     *
     */
    private $price;

//    /**
//     * @var bool
//     *
//     * @ORM\Column(name="published", type="boolean")
//     *
//     */
//    private $published;


    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category",inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     */
    private $category;



    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255 )
     *
     * @Assert\Image(mimeTypes={"image/png", "image/jpeg"}, maxSize="5M")
     */
    private $image;
    /**
     *
     * @Assert\Image(mimeTypes={"image/png", "image/jpeg"}, maxSize="5M")
     */
    private $image_form;



    /**
     * @var array
     * @ORM\Column(name="tags", type="array")
     *
     *  @Assert\NotBlank()
     */
    private $tags;


    /**
     * @var Stock
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Stock", mappedBy="product")
     *
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="products")
     *  @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     */
    private $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param Category $category
     *
     * @return Product
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Product
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

//    /**
//     * Set published
//     *
//     * @param boolean $published
//     *
//     * @return Product
//     */
//    public function setPublished($published)
//    {
//        $this->published = $published;
//
//        return $this;
//    }
//
//    /**
//     * Get published
//     *
//     * @return bool
//     */
//    public function getPublished()
//    {
//        return $this->published;
//    }
    /**
     * @return Stock
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param Stock $stock
     *
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getImageForm()
    {
        return $this->image_form;
    }

    /**
     * @param mixed $image_form
     */
    public function setImageForm($image_form)
    {
        $this->image_form = $image_form;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


}

