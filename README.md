# Dynamic-Contact-Form Package for Laravel

## Installation

### Composer

To include the package in your project, Please run following command.

```
composer require webpress/dynamic-contact-form
```

## Migration

Run the following commands to create table
```
php artisan migrate
```
## Config
Run the following commands to publish configuration file:
```php
php artisan vendor:publish --provider="VCComponent\Laravel\ConfigContact\Providers\ConfigContactServiceProvider"
```

Example: Contact page can have 3 positions appear contact form
In ``dynamic-contact-form.php`` you can define the page and the location of the contact form as follows:
```php
<?php
return [
 ....
    'page'            => [
        'contact' => [
            'label'    => 'Contact',
            'position' => [
                'position-1' => 'On the left',
                'position-2' => 'On the right',
                'position-3' => 'Main position',
            ],
        ],
    ],
];
```
## Kernel
```php
protected $middleware = [
  .....
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class
];
```
## View/Composer & AppServiceProvider
Create ```ContactFormComposer.php``` :
```php
use VCComponent\Laravel\ConfigContact\Entites\ContactForm;
...
class ContactFormComposer{
    public function compose(View $view)
    {
        $contact_form = new ContactForm;
        $view->with('contact_form', $contact_form);
    }
}
```
In ```AppServiceProvider.php``` :
```php
use App\Http\View\Composers\ContactFormComposer;
...
public function boot()
{
    View::composer('[The.page.has.a.contact.form]', ContactFormComposer::class);
}
```
## Front-end
In `contact.blade.php` use the following codes to show contact form:
```php
 @if ($errors->any())
    <div class="alert alert-danger">
        <ul ">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@include('contact_form::show-contact-form', ['page' => 'contact', 'position ' => 'position-1' ])

```
## Routes

The api endpoint should have these format:
| Verb      | URI                                             | Action            |
| --------- | ----------------------------------------------- | ----------------- |
| Admin     |                                                 |                   |
| GET       | /api/admin/contact-form/                        | Index             |
| GET       | /api/admin/contact-form/{id}                    | Show              |
| POST      | /api/admin/contact-form                         | store             |
| PUT       | /api/admin/contact-form/{id}                    | Update            |
| DELETE    | /api/admin/contact-form/{id}                    | Destroy           |
| PUT       | /api/admin/contact-form/{id}/change-status      | Change status     |
| GET       | /api/admin/contact-forms/list                   | List              |
| ------    | ----------------------------------              | --------          |
| GET       | /api/admin/contact-form-input/{id}              | show              |
| POST      | /api/admin/contact-form-input                   | Store             |
| PUT       | /api/admin/contact-form-input/{id}              | Update            |
| DELETE    | /api/admin/contact-form-input/{id}              | Destroy           |
| ------    | ----------------------------------              | --------          |
| GET       | /api/admin/contact-form-value                   | Index             |
| GET       | /api/admin/contact-form-value/{id}              | show              |
| PUT       | /api/admin/contact-form-value/{id}              | Update            |
| DELETE    | /api/admin/contact-form-value/{id}              | Destroy           |
| PUT       | /api/admin/contact-form-value/{id}/status       | Change status     |
| ------    | ----------------------------------              | --------          |
| GET       | /api/admin/contact-form/{id}/contact-form-value | Get payload       |
| GET       | /api/admin/contact-form/get-page-list           | Get page list     |
| GET       | /api/admin/get-position-list/{slug}             | Get position list |
| Front-end |                                                 |                   |
| POST      | /send-contact-infor                             | Store             |



