<article id="component-sortable" class="sandbox-block">
    <header>
        <h5>{@component.sortable}</h5>
    </header>
    <div class="content">
        <ul class="sortable-block">
            <li class="sortable-element">
                <div class="sortable-selector" aria-label="{@component.sortable.move}"></div>
                <div class="sortable-title">
                    <span><a>{@component.static.sortable}</a></span>
                </div>
            </li>
            <li class="sortable-element dragged" style="position: relative;">
                <div class="sortable-selector" aria-label="{@component.sortable.move}"></div>
                <div class="sortable-title">
                    <span><a>{@component.moving.sortable}</a></span>
                </div>
            </li>
            <li>
                <div class="dropzone">{@component.dropzone}</div>
            </li>
        </ul>
    </div>
    <!-- Source code -->
    <div class="formatter-container formatter-hide no-js tpl">
        <span class="formatter-title title-perso">{@sandbox.source.code} :</span>
        <div class="formatter-content formatter-code">
            <div class="formatter-content"><pre class="language-html line-numbers"><code class="language-html">&lt;ul class="sortable-block">
    &lt;li class="sortable-element">
        &lt;div class="sortable-selector" aria-label="{@component.sortable.move}">&lt;/div>
        &lt;div class="sortable-title">
            &lt;span>&lt;a>{@component.static.sortable}&lt;/a>&lt;/span>
        &lt;/div>
    &lt;/li>
    &lt;li class="sortable-element dragged" style="position: relative;">
        &lt;div class="sortable-selector" aria-label="{@component.sortable.move}">&lt;/div>
        &lt;div class="sortable-title">
            &lt;span>&lt;a>{@component.moving.sortable}&lt;/a>&lt;/span>
        &lt;/div>
    &lt;/li>
    &lt;li>
        &lt;div class="dropzone">{@component.dropzone}&lt;/div>
    &lt;/li>
&lt;/ul></code></pre>
            </div>
        </div>        
    </div>
</article>
