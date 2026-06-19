@php($map = [
    'demandee'  => 'bg-terracotta/10 text-terracotta-700 border-terracotta/20',
    'prix_propose'   => 'bg-amber-50 text-amber-700 border-amber-200',
    'contre_propose' => 'bg-amber-50 text-amber-700 border-amber-200',
    'acceptee'  => 'bg-lagon-50 text-lagon-700 border-lagon-100',
    'en_route'  => 'bg-lagon-50 text-lagon-700 border-lagon-100',
    'arrive'    => 'bg-lagon-50 text-lagon-700 border-lagon-100',
    'attente_client' => 'bg-terracotta/10 text-terracotta-700 border-terracotta/20',
    'en_course' => 'bg-lagon-50 text-lagon-700 border-lagon-100',
    'terminee'  => 'bg-green-50 text-green-700 border-green-200',
    'annulee'   => 'bg-sable-50 text-nuit/50 border-sable-200',
])
<span class="inline-flex rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $map[$course->statut] ?? 'bg-sable-50 text-nuit/60 border-sable-200' }}">
    {{ $course->statutLabel() }}
</span>
