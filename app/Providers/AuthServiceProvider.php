use App\Models\Squad;
use App\Policies\SquadPolicy;

protected $policies = [
    Squad::class => SquadPolicy::class,
];
