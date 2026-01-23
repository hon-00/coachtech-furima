<p>{{ optional($transaction->seller)->name ?? '出品者' }} 様</p>

<p>商品「{{ optional($transaction->item)->name ?? '（不明な商品）' }}」の取引が完了しました。</p>

<p>購入者：{{ optional($transaction->buyer)->name ?? '購入者' }}</p>

<p>ご確認ください。</p>