# Plank


#### Welcome to Plank

This proyect is in development and increasing its functionalities. In its latest update incorporates "collections" that are a simple way to generate an api rest by declaring its structure in a file. The structure duplicate itself, update and do back ups in the database.

## New features
A collection automatically creates an api rest. For this is necessary to configure the connection to the database.

The configuration file is application/config/database.php
```php
//file: application/collections/example.php
class Example extends Collection
{
    public function __construct()
    {
        parent::__construct();
        //with this structure you can build a rest api
        $this->structure = [
          'fullname' => 'text',
          'date2' => 'timestamp',
          'lastname' => 'text'
        ];
    }
}
```
The api explose in yourdomain.example/api/:collection
#### Create record in database:
```
curl -X POST -H "Content-Type: application/x-www-form-urlencoded" -d 'fullname=pepino&lastname=asdasd' "http://localhost/plank/api/example/"
```
Response:
```json
{
  "success": true,
  "data": {
    "fullname": "pepino",
    "date2": "2017-02-02 15:36:59",
    "lastname": "asdasd",
    "_id": "3"
  }
}
```
#### Get all record in database:
```
curl -X GET -H "Content-Type: application/x-www-form-urlencoded" "http://localhost/plank/api/example/"
```
Response:
```json
{
  "success": true,
  "data": [
    {
      "fullname": "nikola tesla",
      "date2": "2017-02-02 12:33:25",
      "lastname": "tesla",
      "_id": "1"
    },
    {
      "fullname": "max planck",
      "date2": "2017-02-02 12:33:52",
      "lastname": "planck",
      "_id": "2"
    },
    {
     "fullname": "pepino",
     "date2": "2017-02-02 15:36:59",
     "lastname": "asdasd",
     "_id": "3"
    }
  ]
}
```
#### Update Record:
```
curl -X PUT -H "Content-Type: application/x-www-form-urlencoded" -d 'fullname=pepino&lastname=plank' "http://localhost/plank/api/example/3"
```
Response:
```json
{
  "success": true,
  "data": {
    "fullname": "pepino",
    "date2": "2017-02-02 15:36:59",
    "lastname": "plank",
    "_id": "3"
  }
}
```
### Delete Record:
```
curl -X DELETE -H "Content-Type: application/x-www-form-urlencoded" -d 'fullname=pepino&lastname=plank' "http://localhost/plank/api/example/3"
```
Response:
```json
{
  "success": true,
  "message": "Document Deleted."
}
```
### validation
The validation is done just as in the example within an associative array. Possible options are: max_lenth, min_length and required.

```php
//file: application/collections/example.php
class Example extends Collection
{
    public function __construct()
    {
        parent::__construct();
        //with this structure you can build a rest api
        $this->structure = [
          'fullname' => 'text',
          'date2' => 'timestamp',
          'lastname' => 'text'
        ];
        $this->validation = [
          'fullname'=>["min_length"=>2,"max_length"=>100,"required"=>true],
          'lastname'=>["required"=>true]
        ];
    }
}
```
### Access rest methods
A collection can receive in the parent constructor an array with the enabled methodss

```php
class Bands extends Collection
{
    public function __construct()
    {
        parent::__construct(['GET','POST']);
        $this->structure = [
          'fullname' => 'text',
          'date2' => 'timestamp',
          'lastname' => 'text'
        ];
        $this->validation = [
          'fullname'=>["min_length"=>2,"max_length"=>100,"required"=>true],
          'lastname'=>["required"=>true]
        ];
    }
}
```
You can add an access token for all the methods in the configuration file

    application/config/collection.php


### Use (MVC old functionality)
[Detail on the wiki.](https://github.com/ivanhuay/plank/wiki/MVC-functionality)
