<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @ORM\Table(name="symfony_demo_post")
 *
 * Defines the properties of the Post entity to represent the blog posts.
 * See http://symfony.com/doc/current/book/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See http://symfony.com/doc/current/cookbook/doctrine/reverse_engineering.html
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class Post
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    const NUM_ITEMS = 10;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="json_array")
     */
    private $title;

    /**
     * @Assert\NotBlank()
     */
    private $title_assert;

    /**
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\Column(type="json_array")
     */
    private $summary;

    /**
     * @Assert\NotBlank(message="post.blank_summary")
     */
    private $summary_assert;

    /**
     * @ORM\Column(type="json_array")
     */
    private $content;

    /**
     * @Assert\NotBlank(message="post.blank_content")
     * @Assert\Length(min = "10", minMessage = "post.too_short_content")
     */
    private $content_assert;

    /**
     * @ORM\Column(type="string")
     * @Assert\Email()
     */
    private $authorEmail;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $publishedAt;

    /**
     * @ORM\OneToMany(
     *      targetEntity="Comment",
     *      mappedBy="post",
     *      orphanRemoval=true
     * )
     * @ORM\OrderBy({"publishedAt" = "DESC"})
     */
    private $comments;

    // used for display
    private $locale = "en";

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
        $this->comments = new ArrayCollection();
    }

    public function getAsserts()
    {
        $this->title_assert = $this->getTitle();
        $this->summary_assert = $this->getSummary();
        $this->content_assert = $this->getContent();
    }

    public function setAsserts()
    {
        $this->setTitle($this->title_assert);
        $this->setSummary($this->summary_assert);
        $this->setContent($this->content_assert);
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return array_key_exists($this->locale, $this->title) ? $this->title[$this->locale] : "";
    }

    public function getTitleAssert()
    {
        return $this->title_assert;
    }

    public function setTitle($title)
    {
        $this->title[$this->locale] = $title;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getContent()
    {
        return array_key_exists($this->locale, $this->content) ? $this->content[$this->locale] : "";
    }

    public function getContentAssert()
    {
        return $this->content_assert;
    }

    public function setContent($content)
    {
        $this->content[$this->locale] = $content;
    }

    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;
    }

    /**
     * Is the given User the author of this Post?
     *
     * @param User $user
     *
     * @return bool
     */
    public function isAuthor(User $user)
    {
        return $user->getEmail() === $this->getAuthorEmail();
    }

    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function addComment(Comment $comment)
    {
        $this->comments->add($comment);
        $comment->setPost($this);
    }

    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    public function getSummary()
    {
        return array_key_exists($this->locale, $this->summary) ? $this->summary[$this->locale] : "";
    }

    public function getSummaryAssert()
    {
        return $this->summary_assert;
    }

    public function setSummary($summary)
    {
        $this->summary[$this->locale] = $summary;
    }

    /**
     * Set title_assert
     *
     * @param array $titleAssert
     * @return Post
     */
    public function setTitleAssert($titleAssert)
    {
        $this->title_assert = $titleAssert;

        return $this;
    }

    /**
     * Set summary_assert
     *
     * @param array $summaryAssert
     * @return Post
     */
    public function setSummaryAssert($summaryAssert)
    {
        $this->summary_assert = $summaryAssert;

        return $this;
    }

    /**
     * Set content_assert
     *
     * @param array $contentAssert
     * @return Post
     */
    public function setContentAssert($contentAssert)
    {
        $this->content_assert = $contentAssert;

        return $this;
    }
}
