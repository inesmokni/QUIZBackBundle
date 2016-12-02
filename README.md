# QUIZBackBundle
 This bundle aims to create and manage multiple quiz.
 
# Features
  - Create many quiz at the time and makes only one active.
  - A quiz can have multiple categories. Each category have many pages and each page have many questions.
  - Each component can be confiured by simple parameters.
  - Clone existing quiz
  - A question can be conditionned by one or many other question's responses

# Install QUIZBackBundle

1/ Add require to your composer.json and update:

    "pxquiz/back-bundle" : "dev-master"
   
2/ Update your AppKernel.php:

    new QUIZ\BackBundle\QUIZBackBundle()

3/ Update your assets :

    php app/console assets:install

4/ Add the custom themes uder Twig in your config.yml :

    twig:
      form_themes:
        - 'QUIZBackBundle:Form:_parent_view_type-prototype.html.twig'
        - 'QUIZBackBundle:Form:_quiz_response_type-prototype.html.twig'
        
5/ Include the bundle routes in routing.yml:

    quiz_back:
      resource: "../../vendor/pxquiz/back-bundle/Controller/"
      type:     annotation
      prefix:   /
    
6/ Use a bundle wich extends QUIZBackBundle:

      public function getParent()
      {
        return 'QUIZBackBundle';
      }
    
7/ Update your database:

    php app/console doctrine:schema:update --force 
   

8/ Type this route and follow the instructions: 
      http://YOUR_HOST/quiz/
      
9/ In order to be able to configure your Quiz, use this pattern in config.yml : 

    quiz_back:
          categories: 
            C01: Catéagorie 1
            C02: Catéagorie 2
            C03: Catéagorie 3
          question_type:
            0: Texte libre
            1: Radio
            2: Checkbox
            3: Combobox
          question_has_score : false
          question_has_condition: false
          question_has_help: false
          extra_response: false
 
 
