<div class="card flex flex-col mt-3 text-default" style="min-height: 200px;">
                        <h3 class="font-normal text-xl py-4 -ml-5 border-l-4 border-blue-lighter pl-4">
                            Invite a User
                        </h3>
                        
                            <form class="" method="POST" action="{{ route('projects.invite', $project) }}">
                    
                                @csrf
                                @method('PATCH')

                                <div>
                                    <input type="text" name="email" placeholder="Email address" class="border border-gray w-full mb-3 p-1">
                                </div>

                                <button type="submit" class="text-xs btn">Invite</button>
                                
                            </form>
                            @include('projects._errors', ['bag' => 'invitations'])
                    </div>