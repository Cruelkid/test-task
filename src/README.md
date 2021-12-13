1. Run 'docker-compose up -d'
2. cd into 'src' and run:
    - php bin/console doctrine:migrations:migrate (when prompted hit "Enter")
    - php bin/console doctrine:fixtures:load (when prompted type 'yes' and hit "Enter")
3. Now you are able to go to http://localhost:8102/ to access database. Login: root , Password: root
4. Request examples:
    - Country:
        - GET: curl --location --request GET 'http://localhost:8101/api/countries' --header 'locale: en'
        - GET: curl --location --request GET 'http://localhost:8101/api/countries/1' --header 'locale: en'
        - POST: curl --location --request POST 'http://localhost:8101/api/countries' --header 'locale: ua' --header 'Content-Type: application/json' --data-raw '{"name": "NEW_COUNTRY","locale": "en","vatRates": [10]}'
        - PUT: curl --location --request PUT 'http://localhost:8101/api/countries/3' --header 'locale: en' --header 'Content-Type: application/json' --data-raw '{"name": "UPDATED_COUNTRY","locale": "ua","vatRates": [10]}'
        - DELETE: curl --location --request DELETE 'http://localhost:8101/api/countries/3' --header 'locale: en'

    - VatRate:
        - GET: curl --location --request GET 'http://localhost:8101/api/vatRates' --header 'locale: en'
        - GET: curl --location --request GET 'http://localhost:8101/api/vatRates/1' --header 'locale: en'
        - POST: curl --location --request POST 'http://localhost:8101/api/vatRates' --header 'locale: en' --header 'Content-Type: application/json' --data-raw '{"rate": 100500,"country": "Great Britain"}'
        - PUT: curl --location --request PUT 'http://localhost:8101/api/vatRates/5' --header 'locale: en' --header 'Content-Type: application/json' --data-raw '{"rate": 22,"country": "Ukraine"}'
        - DELETE: curl --location --request DELETE 'http://localhost:8101/api/vatRates/5' --header 'locale: en'

    - Locale:
        - GET: curl --location --request GET 'http://localhost:8101/api/locales' --header 'locale: en'
        - GET: curl --location --request GET 'http://localhost:8101/api/locales/1' --header 'locale: en'
        - POST: curl --location --request POST 'http://localhost:8101/api/locales' --header 'locale: en' --header 'Content-Type: application/json' --data-raw '{"name": "TEST_LOCALE","isoCode": "tl"}'
        - PUT: curl --location --request PUT 'http://localhost:8101/api/locales/3' --header 'locale: en' --header 'Content-Type: application/json' --data-raw '{"name": "UPDATED_LOCALE","isoCode": "ul"}'
        - DELETE: curl --location --request DELETE 'http://localhost:8101/api/locales/3' --header 'locale: en'

    - Product:
        - GET: curl --location --request GET 'http://localhost:8101/api/products' --header 'locale: en'
        - GET: curl --location --request GET 'http://localhost:8101/api/products/1' --header 'locale: en'
        - POST: curl --location --request POST 'http://localhost:8101/api/products' --header 'locale: en' --header 'Content-Type: application/json' --data-raw '{"name": "TEST_PRODUCT","description": "TEST_DESCRIPTION","price": 13.66,"vatRate": 10}'
        - PUT: curl --location --request PUT 'http://localhost:8101/api/products/7' --header 'locale: en' --header 'Content-Type: application/json' --data-raw '{"name": "UPDATED_PRODUCT","description": "UPDATED_DESCRIPTION","price": 6,"vatRate": 5.5}'
        - DELETE: curl --location --request DELETE 'http://localhost:8101/api/products/7' --header 'locale: en'
