# Dynamic-Contact-Form Package for Laravel

## Installation

### Composer

To include the package in your project, Please run following command.

```
Comming soon
```

## Migration

Run the following commands to publish configuration and migration files.


Create tables:

```
php artisan migrate
```
## Kernel

```php
protected $middleware = [
  .....
    \Illuminate\Session\Middleware\StartSession::class,

    \Illuminate\View\Middleware\ShareErrorsFromSession::class,

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
    View::composer('pages.contact', ContactFormComposer::class);
}
```
## Front-end
In `contact.blade.php` use this code to show contact form:
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
@include('contact_form::show-contact-form')

```
## Routes

The api endpoint should have these format:
| Verb   | URI                                | Action   |
| ------ | ---------------------------------- | -------- |
| Admin  |                                    |          |
| GET    | /api/admin/contact-form/           | Index    |
| GET    | /api/admin/contact-form/{id}       | Show     |
| POST   | /api/admin/contact-form            | store    |
| PUT    | /api/admin/contact-form/{id}       | Update   |
| DELETE | /api/admin/contact-form/{id}       | Destroy  |
| ------ | ---------------------------------- | -------- |
| POST   | /api/admin/contact-form-input      | Store    |
| PUT    | /api/admin/contact-form-input/{id} | Update   |
| DELETE | /api/admin/contact-form-input/{id} | Destroy  |
| ------ | ---------------------------------- | -------- |
| GET    | /api/admin/contact-form-value      | Index    |
| GET    | /api/admin/contact-form-value/{id} | Show     |
| PUT    | /api/admin/contact-form-value/{id} | Update   |
| DELETE | /api/admin/contact-form-value/{id} | Destroy  |

| Front-end |                                    |          |
| POST      | /send-contact-infor                | Store    |



