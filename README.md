# laravel-zimbra

<p>
    <a href="https://travis-ci.org/diegosouza/laravel-zimbra"><img src="https://travis-ci.org/diegosouza/laravel-zimbra.svg?branch=master" alt="Build Status"></img></a>
    <a href="https://scrutinizer-ci.com/g/diegosouza/laravel-zimbra"><img src="https://scrutinizer-ci.com/g/diegosouza/laravel-zimbra/badges/quality-score.png?b=master" alt="Quality Score"></img></a>
    <a href="https://packagist.org/packages/diegosouza/laravel-zimbra"><img src="https://poser.pugx.org/diegosouza/laravel-zimbra/d/total.svg" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/diegosouza/laravel-zimbra"><img src="https://poser.pugx.org/diegosouza/laravel-zimbra/v/stable.svg" alt="Latest Stable Version"></a>
</p>

## Quick start

Install the package:

```bash
composer require diegosouza/laravel-zimbra --dev
```

Publish the configuration file using:

```bash
php artisan vendor:publish --provider="DiegoSouza\Zimbra\ZimbraServiceProvider"
```

Then provide the values to `config/zimbra.php`.


## Usage

The wrapper class is `DiegoSouza\Zimbra\ZimbraApiClient`. You can have it injected somewhere:

```php
namespace App\Http\Controllers;
  
use DiegoSouza\Zimbra\ZimbraApiClient;
  
class YourController extends Controller
{
    public function index(ZimbraApiClient $zimbra)
    {
        $result = $zimbra->getAllCos();

        // use the api result
    }
}
```

Or you can use it's methods through the Facade:

```php
namespace App\Http\Controllers;
  
use DiegoSouza\Zimbra\Facades\Zimbra;
  
class YourController extends Controller
{
    public function index()
    {
        $result = Zimbra::getAllCos();

        // use the api result
    }
}
```