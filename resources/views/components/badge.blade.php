@props(['group', 'value'])
<span class="badge tone-{{ \App\Support\Status::tone($group, $value) }}">{{ \App\Support\Status::label($group, $value) }}</span>
