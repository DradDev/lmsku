@php
    $selectedSkillIds = $question->skills->pluck('id')->toArray();
    $mainSkillId = optional($question->skills->firstWhere('parent_id', null))->id;
@endphp

<div class="mb-4">
    <label class="block font-semibold mb-2">Bidang / Skill Utama</label>

    <select id="main_skill_select" name="main_skill_id" class="border rounded w-full p-2">
        <option value="">-- Pilih Bidang Utama --</option>

        @foreach ($mainSkills as $mainSkill)
            <option
                value="{{ $mainSkill->id }}"
                @selected((int) old('main_skill_id', $mainSkillId) === (int) $mainSkill->id)
            >
                {{ $mainSkill->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="block font-semibold mb-2">Detail Skill yang Diuji</label>

    @foreach ($mainSkills as $mainSkill)
        <div class="skill-detail-group hidden" data-parent-id="{{ $mainSkill->id }}">
            <div class="border rounded p-3 mb-3">
                <p class="font-semibold mb-2">
                    Detail {{ $mainSkill->name }}
                </p>

                @forelse ($mainSkill->children as $childSkill)
                    <label class="block mb-1">
                        <input
                            type="checkbox"
                            name="skill_ids[]"
                            value="{{ $childSkill->id }}"
                            @checked(in_array($childSkill->id, old('skill_ids', $selectedSkillIds)))
                        >
                        {{ $childSkill->name }}
                    </label>
                @empty
                    <p class="text-sm text-gray-500">
                        Belum ada detail skill untuk bidang ini.
                    </p>
                @endforelse
            </div>
        </div>
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainSkillSelect = document.getElementById('main_skill_select');
        const detailGroups = document.querySelectorAll('.skill-detail-group');

        function showSelectedSkillDetails() {
            const selectedMainSkillId = mainSkillSelect.value;

            detailGroups.forEach(function (group) {
                if (group.dataset.parentId === selectedMainSkillId) {
                    group.classList.remove('hidden');
                } else {
                    group.classList.add('hidden');

                    group.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                        checkbox.checked = false;
                    });
                }
            });
        }

        mainSkillSelect.addEventListener('change', showSelectedSkillDetails);
        showSelectedSkillDetails();
    });
</script>