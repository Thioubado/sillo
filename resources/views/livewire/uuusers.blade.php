<div>

    <style>
        tr>th {
            /* background-color: #aaa; */
            color: greenyellow;
        }
    </style>

    <x-header title="Uuusers" shadow separator progress-indicator />

    <div class="field mb-5">
        <x-input type="text" placeholder="Rechercher un membre" icon="o-magnifying-glass"
            wire:model.live.debounce.300ms="search" focus />
    </div>

    <table class="w-full mb-3">
        <thead>
            <tr>
                <th class="rounded-tl-lg text-center">#</th>
                <th>Name</th>
                <th>Title</th>
                <th>Role</th>
                <th class>Status</th>
                <th class="rounded-tr-lg">&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            @if ($users)
                @foreach ($users as $user)
                    <tr>
                        <td class="text-right">{{ $user->id }}</td>
                        <td>
                            <span class="font-bold">
                                {{ $user->name }} {{ $user->firstname }}
                            </span><br>
                            {{ $user->email }}

                        </td>
                        <td><span class="font-bold mr-2">
                                {{ ucfirst($user->role) }}
                            </span>

                        </td>

                        <td>
                            @if ($user->isStudent)
                                <x-icon name="o-academic-cap" class="w-7 h-7 text-cyan-400" />
                            @else
                                <x-icon name="o-user" class="w-7 h-7 text-gray-400" />
                            @endif
                        </td>

                        <td>
                            @if ($user->valid)
                                <x-icon-check />
                            @else
                                <x-icon-novalid />
                            @endif
                        </td>
                        <td></td>
                    </tr>
                @endforeach
            @endif

            <tr class="w-full">
                <td colspan="6" class="p-0">
                    <div class="overflow-x-auto">
                        {{  $users->links() }}
                    </div>
                </td>
            </tr>

        </tbody>
    </table>

</div>
