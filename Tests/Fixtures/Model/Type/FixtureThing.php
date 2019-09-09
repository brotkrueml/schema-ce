<?php
declare(strict_types = 1);

namespace Brotkrueml\SchemaRecords\Tests\Fixtures\Model\Type;

use Brotkrueml\Schema\Core\Model\AbstractType;

final class FixtureThing extends AbstractType
{
    protected $name;
    protected $description;
    protected $url;
    protected $image;
    protected $flag;
    protected $date;
}
