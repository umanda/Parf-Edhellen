@inject('link', 'App\Helpers\LinkHelper')

<blockquote itemscope="itemscope" itemtype="http://schema.org/Article" id="translation-block-{{ $gloss->id }}"
  @if (!$gloss->is_canon)
      class="contribution" 
  @endif>
  <h3 rel="trans-word" class="trans-word">
    @if (!$gloss->is_canon || $gloss->is_uncertain || !$gloss->is_latest)
    <a href="about.page?browseTo=unverified" title="Unverified or debatable content."><span class="glyphicon glyphicon-question-sign"></span></a>
    @endif
    <span itemprop="headline">
      {{ $gloss->word }}
    </span>
  </h3> 
  @if ($gloss->tengwar != null)
  &#32;<span class="tengwar">{{ $gloss->tengwar }}</span>
  @endif
  @if ($gloss->type != 'unset')
    <span class="word-type" rel="trans-type">{{ $gloss->type }}.</span>
  @endif
  <span rel="trans-translation" itemprop="keywords">{{ $gloss->translation }}</span>

  <p class="word-comments" rel="trans-comments" itemprop="articleBody">{!! $gloss->comments !!}</p>

  <footer>
    @if (!empty($gloss->source))
      <span class="word-source" rel="trans-source">[{{ $gloss->source }}]</span>
    @endif
  
    @if (!empty($gloss->etymology))
      <span class="word-etymology" rel="trans-etymology">{{ $gloss->etymology }}.</span>
    @endif
  
    @if ($gloss->translation_group_id != null)
      Group: <span itemprop="sourceOrganization">{{ $gloss->translation_group_name }}</span>.
    @endif
  
    Published <span itemprop="datePublished">{{ $gloss->created_at }}</span> by 
    <a href="{{ $link->author($gloss->AuthorID, $gloss->acccount_name) }}" itemprop="author" rel="author" title="View profile for {{ $gloss->account_name }}.">
      {{ $gloss->account_name }}
    </a>
  </footer>
</blockquote>
