<x-app-layout>

    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
           Candidate  Dashboard
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">

        <!-- Statistics -->

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-gray-500">Applied Jobs</h3>
                <h2 class="text-3xl font-bold text-blue-600">12</h2>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-gray-500">Saved Jobs</h3>
                <h2 class="text-3xl font-bold text-green-600">8</h2>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-gray-500">Interviews</h3>
                <h2 class="text-3xl font-bold text-orange-600">3</h2>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-gray-500">Profile Completion</h3>
                <h2 class="text-3xl font-bold text-purple-600">85%</h2>
            </div>

        </div>

        <!-- Welcome Card -->

        <div class="bg-white rounded-xl shadow mt-8 p-6">

            <h3 class="text-xl font-bold">
                Welcome, {{ Auth::user()->name }}
            </h3>

            <p class="text-gray-500 mt-2">
                Manage your profile, upload resume and apply for jobs.
            </p>

        </div>

        <!-- Recent Jobs -->

        <div class="bg-white rounded-xl shadow mt-8 p-6">

            <h3 class="text-xl font-bold mb-4">
                Recent Applications
            </h3>

            <table class="w-full">

                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3">Job</th>
                        <th class="text-left py-3">Company</th>
                        <th class="text-left py-3">Status</th>
                    </tr>
                </thead>

                <tbody>

                    <tr class="border-b">
                        <td class="py-3">Laravel Developer</td>
                        <td class="py-3">ABC Technologies</td>
                        <td class="py-3 text-green-600">
                            Shortlisted
                        </td>
                    </tr>

                    <tr class="border-b">
                        <td class="py-3">Project Manager</td>
                        <td class="py-3">XYZ Pvt Ltd</td>
                        <td class="py-3 text-yellow-600">
                            Under Review
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</x-app-layout>
