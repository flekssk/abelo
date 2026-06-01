<section>
    <div class="section-header" style="flex-direction: column; align-items: flex-start; gap: 15px;">
        <div>
            <h1 class="section-title">{$category.name|escape}</h1>
            <p class="section-desc">{$category.description|escape}</p>
        </div>

        <!-- Сортировка -->
        <div style="display: flex; gap: 10px; background: var(--card-bg); padding: 5px; border-radius: 6px; border: 1px solid var(--border);">
            <span style="padding: 5px 10px; color: var(--text-muted); font-size: 14px;">Сортировка:</span>
            <a href="/category/{$category.id}?sort=date" class="btn {if $sort == 'date'}btn-fill{else}btn-outline{if/}" style="padding: 5px 10px; font-size: 12px;">По дате</a>
            <a href="/category/{$category.id}?sort=views" class="btn {if $sort == 'views'}btn-fill{else}btn-outline{if/}" style="padding: 5px 10px; font-size: 12px;">По просмотрам</a>
        </div>
    </div>

    <!-- Сетка постов категории -->
    <div class="grid">
        {foreach from=$posts item=post}
            <article class="card">
                <img src="{$post.image|default:'https://placeholder.com'}" alt="{$post.title|escape}" class="card-img">
                <div class="card-body">
                    <h3 class="card-title">{$post.title|escape}</h3>
                    <p class="card-text">{$post.description|escape}</p>
                    <div class="card-meta">
                        <span>👁 {$post.views}</span>
                        <span>📅 {$post.created_at|date_format:"%d.%m.%Y"}</span>
                    </div>
                    <a href="/post?id={$post.id}" class="btn" style="margin-top: 15px;">Читать полностью</a>
                </div>
            </article>
            {foreachelse}
            <p style="color: var(--text-muted);">В этой категории пока нет статей.</p>
        {/foreach}
    </div>

    <!-- Пагинация -->
    {if $total_pages > 1}
        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 40px;">
            {for $page=1 to $totalPages}
                <a href="/category?id={$category.id}&sort={$current_sort}&page={$page}"
                   class="btn {if $currentPage == $page}btn-fill{else}btn-outline{/if}"
                   style="padding: 8px 14px;">
                    {$page}
                </a>
            {/for}
        </div>
    {/if}
</section>
