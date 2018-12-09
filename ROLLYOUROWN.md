If you feel like you want more features, it's really easy to add your own.

Make a pull request too, it's always nice to have more features.

Don't forget to add tests too!

This example focuses on the Parsers, but the Builder is exactly the same, just changes the class names.

```php
class MoreFeaturesPathBag extends \Keppler\Url\Parser\Bags\PathBag
{
    // more features go here

    public function exampleFunction(): void
    {
        echo 'HERE BE EXTENDED PATH BAG';
    }
}

class MoreFeaturesQueryBag extends \Keppler\Url\Parser\Bags\QueryBag
{
    // more features go here

    public function exampleFunction(): void
    {
        echo 'HERE BE EXTENDED QUERY BAG';
    }
}

class MoreFeaturesParser extends \Keppler\Url\Parser\Parser
{
    public function __construct()
    {
        // DO NOT CALL parent::__construct
        // You want to add your new classes not the old ones
        $this->query = new MoreFeaturesQueryBag();
        $this->path = new MoreFeaturesPathBag();
    }
}

//That's it, you're done

$moreFeaturesParser = new MoreFeaturesParser();
$moreFeaturesParser->query->exampleFunction();
$moreFeaturesParser->path->exampleFunction();
````