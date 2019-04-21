<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-04-20
 * Time: 08:56
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Address;
use AppBundle\Entity\BankDetails;
use AppBundle\Entity\CitizenshipDetails;
use AppBundle\Entity\City;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Contract;
use AppBundle\Entity\Country;
use AppBundle\Entity\Mail;
use AppBundle\Entity\Phone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class BaseFixture extends Fixture
{
    /**
     * @var ObjectManager
     */
    private $manager;

    private $faker;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;

        $faker = Faker\Factory::create('fr_FR');

        $this->faker = $faker;

    }

    public function load(ObjectManager $manager)
    {

        $this->fakeAddresses();

        $this->fakeBankDetails();

        $this->fakeContacts();

        $this->fakeCitizenship();

        $this->fakeMail();

        $this->fakePhone();

    }

    private function valSetter($val1, $val2){

        $value = rand(0,1);
        $value === 0 ? $value = $val1 : $value = $val2;

        return $value;
    }

    public function fakeAddresses(){


        $country = new Country();

        $country->setCountryname('France');

        $this->manager->persist($country);

        for ($i=0; $i<10; $i++){

            $city = new City();
            $address = new Address();

            $address->setStreet($this->faker->streetAddress);
            $address->setPostcode($this->faker->postcode);
            $address->setCity($city);
            $city->setCityname($this->faker->city);
            $city->setCountry($country);

            $this->manager->persist($city);
            $this->manager->persist($address);
        }

        $this->manager->flush();
    }

    public function fakeBankDetails(){

        for($i=0; $i < 10; $i++){

            $bankdetails = new BankDetails();

            $bankdetails->setIban($this->faker->iban('FR'));
            $bankdetails->setBiccode($this->faker->swiftBicNumber);

            $this->manager->persist($bankdetails);

        }

        $this->manager->flush();

    }

    public function fakeContacts(){


        for($i=0;$i<10;$i++){

            $contact = new Contact();

            $contact->setLastname($this->faker->lastName);
            $contact->setFirstname($this->faker->firstName);
            $contact->setDatefbirth($this->faker->dateTimeInInterval());
            $contact->setSexe($this->valSetter('Male', 'Female'));
            $contact->setContacttype($this->valSetter('Prospect','Employee' ));
            $contact->setSocialesecunumber(rand(100000, 3000000000));
            $this->manager->persist($contact);

        }

        $this->manager->flush();

    }


    public function fakeCitizenship(){

        for($i=0; $i < 10; $i++){

            $citizenship = new CitizenshipDetails();

            $citizenship->setDeliveryDate($this->faker->dateTimeThisDecade());
            $citizenship->setDocumentId(rand(100000, 3000000000));

           $citizenship->setDocumentType($this->valSetter('Passport', 'Id Card'));

            $citizenship->setExpireDate($this->faker->creditCardExpirationDate);

            $this->manager->persist($citizenship);

        }

        $this->manager->flush();

    }

    public function fakeContract(){

        for($i=0; $i < 10; $i++){

            $contract = new Contract();

            $contract->setContractType($this->valSetter('CDD', 'CDI'));
            $contract->setStartDate($this->faker->dateTimeThisMonth());
            $contract->setProbationPeriodEndDate($this->faker->dateTimeThisYear());

            $this->manager->persist($contract);
        }

        $this->manager->persist($contract);

    }

    public function fakeMail(){

        for ($i = 0; $i < 10; $i++) {

            $mail = new Mail();

            $mail->setMail($this->faker->email);

            $this->manager->persist($mail);

        }
        $this->manager->persist($mail);

    }

    public function fakePhone(){

        for ($i = 0; $i < 10; $i++) {

            $phone = new Phone();

            $phone->setPhonenumber($this->faker->phoneNumber);

            $this->manager->persist($phone);

        }
        $this->manager->persist($phone);

    }


}