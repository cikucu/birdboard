<div class="bg-white mr-4 p-5 rounded-lg shadow flex flex-col mt-3">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-300 pl-4">
        Invite a User
    </h3>
    
    <form action="{{ $project->path() . '/invitations' }}" method="POST">
            @csrf
        <div class="mb-3">
            <input type="email" name="email" class="border border-grey rounded w-full py-2 px-3" placeholder="email address">
        </div>
        <button type="submit" class="bg-blue-400 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-full"> 
            Invite
        </button>
    </form>
    @include('errors', ['bag' => 'invitations'])
</div>