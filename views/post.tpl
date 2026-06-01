<article style="max-width: 800px; margin: 40px auto 0 auto;">
    <header style="background: transparent; border: none; padding: 0; position: static;">
        <div style="margin-bottom: 15px;">
            <!-- Отображение всех категорий статьи (Many-to-Many) -->
            {foreach from=$post.categories item=cat}
                <a href="/category?id={$cat.id}" class="badge"># {$cat.name|escape}</a>
            {/foreach}
        </div>
        <h1 style="font-size: 42px; line-height: 1.2; margin-bottom: 20px;">{$post.title|escape}</h1>
        <div style="display: flex; gap: 20px; color: var(--text-muted); font-size: 14px; margin-bottom: 30px;">
            <span>📅 Опубликовано: {$post.created_at|date_format:"%d.%m.%Y"}</span>
            <span>👁 Просмотров: {$post.views}</span>
        </div>
    </header>

    <img src="{$post.image|default:'https://placeholder.com'}" alt="{$post.title|escape}"
         style="width: 100%; max-height: 450px; object-fit: cover; border-radius: 12px; margin-bottom: 40px;">

    <!-- Текст статьи -->
    <div style="font-size: 18px; line-height: 1.8; color: #e2e8f0; white-space: pre-line;">
        {$post.text|escape}
    </div>

    <!-- Блок похожих статей -->
    <section style="margin-top: 80px; border-top: 1px solid var(--border); padding-top: 40px;">
        <h2 style="font-size: 24px; margin-bottom: 25px; color: var(--accent);">Похожие статьи</h2>
        <div class="grid">
            {foreach from=$similarPosts item=similar}
                <article class="card" style="box-shadow: none;">
                    <img src="{$similar.image|default:'https://placeholder.com'}" alt="{$similar.title|escape}" class="card-img" style="height: 150px;">
                    <div class="card-body" style="padding: 15px;">
                        <h3 class="card-title" style="font-size: 16px;">{$similar.title|escape}</h3>
                        <a href="/post?id={$similar.id}" class="btn btn-outline" style="margin-top: auto; padding: 6px 12px; font-size: 12px;">Прочесть</a>
                    </div>
                </article>
                {foreachelse}
                <p style="color: var(--text-muted); font-size: 14px;">Похожих статей не найдено.</p>
            {/foreach}
        </div>
    </section>
</article>
