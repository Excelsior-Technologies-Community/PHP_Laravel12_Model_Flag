<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-gray-500 text-sm">Total Flags</h3>
                    <p class="text-2xl font-bold">{{ $flags->count() }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Post Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($flags as $flag)
                        <tr>
                            <td class="px-6 py-4">{{ $flag->flaggable->title ?? 'Deleted Post' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">{{ $flag->reason }}</span>
                            </td>
                            <td class="px-6 py-4 flex gap-2">
                                @if($flag->flaggable)
                                <form action="{{ route('admin.post.delete', $flag->flaggable_id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                                @endif
                                <form action="{{ route('admin.flag.resolve', $flag->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="text-green-600 hover:text-green-900">Resolve</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>