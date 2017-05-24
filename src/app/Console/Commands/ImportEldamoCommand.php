<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use App\Models\{ Language, Speech, Translation, TranslationGroup };
use App\Repositories\TranslationRepository;

class ImportEldamoCommand extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ed-import:eldamo {source}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports definitions from eldamo.json. Transform the XML data source to JSON using EDEldamoParser.exe.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = $this->argument('source');
        if (! file_exists($path)) {
            $this->error($path.' does not exist.');
            return;
        }

        $json = trim(file_get_contents($path));
        $data = json_decode($json);
        unset($json); // free memory as the json payload can be huge!

        if (! $data) {
            $this->error('Failed to process '.$path.'. Error: '.json_last_error_msg().' ('.json_last_error().').');
            $this->error('The JSON file must be saved using UTF-8 encoding (without BOM).');
            return;
        }

        $repository = new TranslationRepository();

        $speechMap = [
            '?'        => '?',
            'adj'      => 'adjective',
            'adv'      => 'adverb',
            'affix'    => 'affix',
            'article'  => 'article',
            'cardinal' => 'cardinal',
            'conj'     => 'conjugation',

            'collective-name' => 'collective name',
            'collective-noun' => 'collective noun',
            'family-name'     => 'family name',
            'fem-name'        => 'feminine name',
            
            'fraction'  => 'fraction',
            'grammar'   => 0, // not supported
            'infix'     => 'infix',
            'interj'    => 'interjection',
            'masc-name' => 'masculine name',
            'n'         => 'noun',
            'ordinal'   => 'ordinal',
            'particle'  => 'particle',
            'phoneme'   => 'phoneme',
            'phonetics' => 0,

            'phonetic-group' => 0,
            'phonetic-rule'  => 0,
            'phrase'         => 0,

            'place-name'  => 'place name',
            'pref'        => 'prefix',
            'prep'        => 'preposition',
            'pron'        => 'pronoun',
            'proper-name' => 'proper name',
            'radical'     => 'radical',
            'root'        => 'root',
            'text'        => 0,
            'suf'         => 'suffix',
            'vb'          => 'verb',

            'interj particle' => 0, // not supported
            'prep adv'        => 'preposition/adverb',
            'n adj'           => 'noun/adjective',
            'adj n'           => 'noun/adjective',
            'n adv'           => 'noun/adverb',
            'adv n'           => 'noun/adverb',
            'interj prep'     => 'interjection/preposition',
            'adv conj'        => 'adverb/conjugation',
            'adv adj'         => 'adverb/adjective',
            'adj adv'         => 'adverb/adjective',
            'adv prep'        => 'preposition and adverb',
            'conj adv'        => 'adverb/conjugation',
            'prep pref'       => 'preposition/prefix',
            'adv interj'      => 'adverb/interjection',
            'interj adv'      => 'adverb/interjection',
            'n vb'            => 'noun/verb'
        ];

        foreach ($speechMap as $key => $id) {
            if (is_numeric($id)) {
                continue;
            }

            $speech = Speech::where('name', $id)
                ->select('id')
                ->first();

            if (! $speech) {
                $speech = new Speech;
                $speech->name = $id;
                $speech->save();
            }

            $speechMap[$key] = $speech->id;
        }


        // Establish a language mapping between Eldamo and Parf Edhellen. This map is based on
        // Eldamo's XSD (xs:simpleType name="language-type") for v0.5.5
        $languageMap = [
            'ad'   => 'adunaic',
            'aq'   => 'ancient quenya',
            'at'   => 'ancient telerin',
            'av'   => 'avarin',
            'bel'  => 0, // _beleriandic_ not supported
            'bs'   => 'black speech',
            'cir'  => 0, // _cirth_ not supported
            'dan'  => 'ossriandric', // <~~ deviation from "danian"!
            'dun'  => 'dunlending',
            'dor'  => 'doriathrin',
            'eas'  => 'easterling',
            'ed'   => 'edain',
            'edan' => 0,
            'eilk' => 'early ilkorin',
            'en'   => 'early noldorin',
            'ent'  => 'entish',
            'eon'  => 0, // 'early old noldorin',
            'eoq'  => 0, // 'early old qenya',
            'ep'   => 'early primitive elvish',
            'eq'   => 'qenya', // <~~ deviation from "early quenya"!
            'et'   => 0, // 'early telerin',
            'fal'  => 0, // 'falathrin',
            'g'    => 'gnomish',
            'ilk'  => 'doriathrin', // <~~ deviation from "ilkorin"!
            'kh'   => 'khuzdul',
            'khx'  => 'khuzdul', // <~~ deviation from "Khuzdul, External"!
            'lem'  => 'lemberin',
            'ln'   => 'noldorin', // <~~ deviation from "late noldorin"!
            'lon'  => 'old noldorin', // <~~ deviation from "late old noldorin"!
            'mp'   => 'middle primitive elvish',
            'mq'   => 'qenya', // <~~ deviation from "middle quenya"!
            'mt'   => 'middle telerin',
            'n'    => 'noldorin',
            'oss'  => 'ossriandric',
            'p'    => 'primitive elvish',
            'pad'  => 'primitive adunaic',
            'nan'  => 'nandorin',
            'ns'   => 'north sindarin',
            'on'   => 'old noldorin',
            'os'   => 'old sindarin',
            'q'    => 'quenya',
            'roh'  => 'rohirric',
            's'    => 'sindarin',
            'sar'  => 0, // _sarati_ not supported
            'sol'  => 'solosimpi',
            't'    => 'telerin',
            'tal'  => 'taliska',
            'teng' => 0, // _tengwar_ not supported
            'un'   => 'undetermined',
            'val'  => 'valarin',
            'van'  => 'quendya', // vanyarin
            'wes'  => 'westron',
            'wos'  => 'wose'
        ];

        $missing = [];
        foreach ($languageMap as $key => $id) {
            if (is_numeric($id)) {
                continue;
            }

            $language = Language::where('name', $id)
                ->select('id')
                ->first();

            if (! $language) {
                $missing[] = $id;
                continue;
            }

            $languageMap[$key] = $language->id;
        }

        if (! empty($missing)) {
            $this->error('Missing the languages: "'.implode('", "', $missing).'". Can\'t proceed.');
            return;
        }

        // Find the Eldamo translation group
        $eldamo = TranslationGroup::where('name', 'Eldamo')->firstOrFail();
        $this->line('Eldamo ID: '.$eldamo->id.'.');
        $this->line('Updating '.count($data).' words.');

        // Find the user account for an existing translation from Eldamo. 
        $existing = $account = Translation::where('translation_group_id', $eldamo->id)
            ->select('account_id')
            ->firstOrFail();

        $c = 1;
        foreach ($data as $t) {
            if (count($t->gloss) < 1 || ($t->gloss[0] == $t->word && substr($t->speech, -4) !== 'name')) {
                $this->line('Ignoring due to lacking gloss: '.$t->word.' '.$t->id);
                continue;
            }

            $ot = Translation::latest()
                ->notIndex()
                ->where('external_id', $t->id)
                ->where('translation_group_id', $eldamo->id)
                ->first();

            $found = $ot !== null;
            
            if (! $found) {
                $ot = new Translation;
                $ot->external_id = $t->id;
                $ot->translation_group_id = $eldamo->id;
                $ot->account_id = $existing->account_id;
            }

            $word = $t->word;
            $sense = $t->category ?: $t->gloss[0];
            $keywords = array_slice($t->gloss, 1);
            
            $ot->is_uncertain = $t->mark === '?' ||
                                $t->mark === '*' ||
                                $t->mark === '‽' ||
                                $t->mark === '!' ||
                                $t->mark === '#';
            $ot->is_rejected  = $t->mark === '-';
            $ot->is_deleted   = 0;
            
            $ot->source       = implode('; ', $t->sources);
            $ot->translation  = $t->gloss[0];

            $ot->language_id  = $languageMap[$t->language] ?: null;
            $ot->speech_id    = $speechMap[$t->speech] ?: null;

            $this->createComments($ot, $t, $keywords);

            if (! $ot->language_id) {
                $this->line($word.': ignoring '.$t->id.'.');
                continue;
            }

            $this->line($c.' '.$t->language.' '.$t->word.': '.($found ? $ot->id : 'new'));

            $repository->saveTranslation($word, $sense, $ot, $keywords, false);
  
            $c += 1;
        }
    }

    function createComments(Translation $ot, \stdClass $t, array $keywords)
    {
        $comments = [];

        if (count($keywords) > 0){ 
            $comments[] = 'Also glossed as “'.implode('”, “', $keywords).'”.';
        }
        
        if (! empty($t->notes)) {
            $comments[] = $t->notes;
        }

        if (count($t->variations)) {
            $comments[] = 'Variations of the word: '.implode(', ', array_map(function ($c) use($t) {
                return '**'.$c->word.'**';
            }, $t->variations)).'.';
        }

        if (count($t->elements)) {
            $comments[] = '   ';
            $comments[] = '*Elements*';
            $comments[] = '   '; // necessary before tables.

            $table = [
                'Word|Gloss|Source',
                '----|-----|------'
            ];

            foreach ($t->elements as $element) {
                if (empty($element->source)) {
                    continue;
                }

                $table[] = $element->word.'|'.$element->gloss.' |'.
                    implode('; ', explode('|', $element->source));
            }

            $comments[] = implode("\n", $table);
        }

        if (count($t->inflections)) {
            $comments[] = '   ';
            $comments[] = '*Inflections*';
            $comments[] = '   '; // necessary before tables.

            $table = [
                'Word|Form|Source',
                '----|----|------'
            ];

            foreach ($t->inflections as $inflection) {
                $table[] = $inflection->word.'|'.$inflection->form.'|'.
                    implode('; ', explode('|', $inflection->source));
            }

            $comments[] = implode("\n", $table);
        }

        if (count($t->elementIn)) {
            $comments[] = '   ';
            $comments[] = '*Element In*';

            $comments = array_merge($comments,
                array_map(function ($c) use($t) {
                    return '* '.($c->language ? '_'.strtoupper($t->language).'._ ' : '').'[['.$c->word.']] '.(empty($c->gloss) ? ' “'.$c->gloss.'”' : '');
                }, $t->elementIn)
            );
        }

        if (count($t->related)) {
            $comments[] = '   ';
            $comments[] = '*Related*';

            $comments = array_merge($comments,
                array_map(function ($c) use($t) {
                    return '* '.($c->language ? '_'.strtoupper($t->language).'._ ' : '').'[['.$c->word.']] '.
                        (! empty($c->gloss) ? '“'.$c->gloss.'” ' : '').(! empty($c->notes) ? $c->notes : '');
                }, $t->related)
            );
        }

        if (count($t->cognates)) {
            $comments[] = '   ';
            $comments[] = '*Cognates*';

            $comments = array_merge($comments,
                array_map(function ($c) {
                    return '* [['.$c->word.(empty($c->gloss) ? ']] “'.$c->gloss.'”' : '');
                }, $t->cognates)
            );
        }

        $ot->comments = implode("   \n", $comments);
    }
}