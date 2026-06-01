{foreach from=$categories item=category}
    <section>
        <div class="section-header">
            <div>
                <h2 class="section-title">{$category.name|escape}</h2>
                <p class="section-desc">{$category.description|escape}</p>
            </div>
            <a href="/category?id={$category.id}" class="btn btn-outline">Все статьи</a>
        </div>

        <div class="grid">
            {foreach from=$category.posts item=post}
                <article class="card">
                    <img src="{$post.image|default:'https://placeholder.com'}" alt="{$post.title|escape}" class="card-img">
                    <div class="card-body">
                        <h3 class="card-title">{$post.title|escape}</h3>
                        <p class="card-text">{$post.description|truncate:120|escape}</p>
                        <div class="card-meta">
                            <span>👁 {$post.views}</span>
                            <span>📅 {$post.created_at|date_format:"%d.%m.%Y"}</span>
                        </div>
                        <a href="/post?id={$post.id}" class="btn" style="margin-top: 15px;">Читать полностью</a>
                    </div>
                </article>
            {/foreach}
        </div>
    </section>
    {foreachelse}
    <p style="text-align: center; padding: 50px; color: var(--text-muted);">Категории и статьи не найдены. Запустите сидинг!</p>
{/foreach}
