<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Loan;
use App\Entity\Borrower;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $doctrine;
    private $faker;
    private $hasher; 
    private $manager;

    public static function getGroups(): array
    {
         return ['test'];     
    }

    public function __construct(ManagerRegistry $doctrine,UserPasswordHasherInterface $hasher)
    {
        $this->doctrine = $doctrine;
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadLoans();
        $this->loadUsers();
        $this->loadAuthors();
        $this->loadBooks();


    }
    
    public function loadLoans(): void
    {
        // borrower
        $repository = $this->manager->getRepository(Borrower::class);
        $borrowers = $repository->findAll();

        // books
        $repository = $this->manager->getRepository(Book::class);
        $books = $repository->findAll();

        $datas = [
            [
                'loanDate'=> '2020-02-01 10:00:00',
                'returnDate'=> '2020-03-01 10:00:00',
                'borrower'=> $borrowers[1],
                'book'=> $books[1],

            ],
            [
                'loanDate'=> '2020-03-01 10:00:00',
                'returnDate'=> '2020-04-01 10:00:00',
                'borrower'=> $borrowers[2],
                'book'=> $books[3],

            ],
            [
                'loanDate'=> '2020-04-01 10:00:00',
                'returnDate'=> Null,
                'borrower'=> $borrowers[2],
                'book'=> $books[2],

            ]
        ];
        foreach($datas as $data) {
            $loans = new Loan();
            $loans->setLoanDate($data['loanDate']);
            $loans->setReturnDate($data['returnDate']);
            $loans->setBook($data['book']);

            foreach ($data['borrower'] as $borrowers) {
                $loans->addBorrower($borrowers);
            }

            $this->manager->persist($loans);
        }
        $this->manager->flush(); 

    }
    
   
    public function loadUsers(): void
    {
        // loans
        $repository = $this->manager->getRepository(Loan::class);
        $loans = $repository->findAll();
        // données de test statiques
        $datas = [
            [
                // user
                'email' => 'admin@example.com',
                'password' => '123',
                'roles' => ['ROLE_ADMIN'],
                'enableds'=> true,
                
            ],
            [
                // user
                'email' => 'foo.foo@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'enableds'=> true,
                // borrower
                'firstname' => 'foo',
                'lastname' => 'foo',
                'tel' => '123456789',
                'loan' => $loans[0], 
                
            ],
            [
                // user
                'email' => 'bar.bar@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'enableds'=> false,
                // borrower
                'firstname' => 'bar',
                'lastname' => 'bar',
                'tel' => '123456789',
                'loan'=> $loans[2],
            ],
            [
                // user
                'email' => 'baz.baz@example.com',
                'password' => '123',
                'roles' => ['ROLE_USER'],
                'enableds'=> true,
                // borrower
                'firstname' => 'baz',
                'lastname' => 'baz',
                'tel' => '123456789',
                'loan'=> $loans[3],
            ],   
        ];

        foreach($datas as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->hasher->hashPassword( $user, $data['password']);
            $user->setPassword($password);
            $user->setRoles($data['roles']);
            $user->setEnableds($data['enableds']);
            
            $borrower = new Borrower();
            $borrower->setFirstname($data['firstname']);
            $borrower->setLastname($data['lastname']);
            $borrower->setTel($data['tel']);
            $borrower->setLoans($data['loans']);



            $this->manager->persist($user);
        }
     
         // données de test dynamiques
         for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email());
            $password = $this->hasher->hashPassword($user,'123' );
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $user->setEnableds($this->faker->boolean());

            $this->manager->persist($user);
            
        }
        
        $this->manager->flush();   
    }
    public function loadAuthors() : void
    {
        $datas = [
            
            [
                'firstname'=> '',
                'lastname'=> '',
            ],
            [
                'firstname'=> 'Hugos',
                'lastname'=> 'Cartier',
            ],
            [
                'firstname'=> 'Armand',
                'lastname'=> 'Lambert',
            ],
            [
                'firstname'=> 'Thomas',
                'lastname'=> 'Moitessier',
            ],

        ];
        foreach($datas as $data){
            $auther = new author();
            $auther->setFirstname($data['firstname']);
            $auther->setLastname($data['lastname']);

            $this->manager->persist($auther);
        } 

        // données de test dynamiques
        for ($i = 0; $i < 500; $i++) {
            $auther = new author();
            $auther->setFirstname($this->faker->firstname());
            $auther->setLastname($this->faker->lastname());

            $this->manager->persist($auther);
        }
        $this->manager->flush(); 

    }
    public function loadBooks():void
    {
        // authors
        $repository = $this->manager->getRepository(Author::class);
        $authors = $repository->findAll();

        $datas = [
            [
                'title'=> 'Lorem ipsum dolor sit amet',
                'yearOfEdition'=> 2010,
                'numberOfPages'=> 100,
                'isbnCode'=> '9785786930024',
                'author'=> $authors[0],
            ],
            [
                'title'=> 'Consectetur adipiscing elit ',
                'yearOfEdition'=> 2011,
                'numberOfPages'=> 150,
                'isbnCode'=> ' 9783817260935 ',
                'author'=> $authors[1],
            ],
            [
                'title'=> 'Mihi quidem Antiochum',
                'yearOfEdition'=> 2011,
                'numberOfPages'=> 200,
                'isbnCode'=> '9782020493727',
                'author'=> $authors[2],
            ],
            [
                'title'=> 'Quem audis satis belle ',
                'yearOfEdition'=> 2013,
                'numberOfPages'=> 250,
                'isbnCode'=> '9794059561353',
                'author'=> $authors[3],
            ],

        ];
        foreach($datas as $data){
            $book = new book();
            $book->setTitle($data['title']);
            $book->setYearOfEdition($data['yearOfEdition']);
            $book->setNumberOfPages($data['numberOfPages']);
            $book->setIsbnCode($data['isbnCode']);
            
            foreach ($data['author'] as $authors) {
                $book->addAuthor($authors);
            }

            $this->manager->persist($book);
        }
        $this->manager->flush(); 
    }

   

    
}




