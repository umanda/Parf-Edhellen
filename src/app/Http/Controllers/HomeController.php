<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Cache,
    Storage
};

use App\Repositories\{
    ContributionRepository,
    StatisticsRepository
};
use App\Repositories\Interfaces\IAuditTrailRepository;
use App\Models\{ 
    AuditTrail,
    Gloss,
    Sentence
};
use App\Adapters\{
    AuditTrailAdapter,
    SentenceAdapter,
    BookAdapter
};

class HomeController extends Controller
{
    protected $_auditTrail;
    protected $_auditTrailAdapter;
    protected $_sentenceAdapter;
    protected $_bookAdapter;
    protected $_reviewRepository;
    protected $_statisticsRepository;

    public function __construct(IAuditTrailRepository $auditTrail, AuditTrailAdapter $auditTrailAdapter, StatisticsRepository $statisticsRepository,
        BookAdapter $bookAdapter, SentenceAdapter $sentenceAdapter, ContributionRepository $contributionRepository) 
    {
        $this->_auditTrail           = $auditTrail;
        $this->_auditTrailAdapter    = $auditTrailAdapter;
        $this->_bookAdapter          = $bookAdapter;
        $this->_sentenceAdapter      = $sentenceAdapter;
        $this->_reviewRepository     = $contributionRepository;
        $this->_statisticsRepository = $statisticsRepository;
    }

    public function index() 
    {
        // Retrieve a random jumbotron background image from configuration. The background is 
        // positioned upon the jumbotron.
        $jumbotronFiles = config('ed.jumbotron_files');
        $noOfJumbotronFiles = count($jumbotronFiles);
        $background = $noOfJumbotronFiles > 0 
            ? $jumbotronFiles[mt_rand(0, $noOfJumbotronFiles - 1)] 
            : null;

        // Retrieve a random sentence to be featured.
        $randomSentence = Cache::remember('ed.home.sentence', 60 * 24 /* minutes */, function () {
            $sentence = Sentence::approved()->inRandomOrder()->first();
            
            return [
                'sentence'     => $sentence,
                'sentenceData' => $sentence 
                    ? $this->_sentenceAdapter->adaptFragments($sentence->sentence_fragments, false) 
                    : null
            ];
        });

        // Retrieve a random gloss to feature
        $randomGloss = Cache::remember('ed.home.gloss', 60 /* minutes */, function () {
            $gloss = Gloss::active()
                ->inRandomOrder()
                ->notUncertain()
                ->first();
            
            return [
                'gloss' => $this->_bookAdapter->adaptGloss($gloss)
            ];
        });

        $statistics = Cache::remember('ed.home.statistics', 60 /* minutes */, function () {
            return $this->_statisticsRepository->getGlobalStatistics();
        });

        // Retrieve the 10 latest audit trail
        $auditTrails = $this->_auditTrailAdapter->adaptAndMerge( $this->_auditTrail->get(10) );
        $data = $randomSentence + $randomGloss + $statistics + [
            'auditTrails'      => $auditTrails,
            'background'       => $background
        ];
        
        return view('home.index', $data);
    }
}
