<?php
if (strpos($user->picture, 'http') === false) {
	$user_picture = !empty($user->picture) ? base_url() . 'profile_picture/' . $user->picture : base_url() . 'images/no-profile-pic.png';
} else {
	$user_picture = $user->picture;
}
$user_name = $user->first_name . ' ' . $user->last_name;
?>

<div class="bg-gradient-to-br from-sky-50 to-gray-200 h-screen flex justify-center items-center">
	<div class="relative container m-auto px-6 text-gray-500 md:px-12 xl:px-40">
		<div class="m-auto md:w-8/12 lg:w-6/12 xl:w-6/12">
			<div class="rounded-xl bg-white shadow-xl">
				<div class="p-6 sm:p-16">
					<div class="flex justify-center mb-10">
						<h1 class="font-bold text-xl">Your Profile Information</h1>
					</div>
					<div class="flex flex-col items-center">
						<img class="w-32 h-auto p-1 rounded-full ring-2 ring-gray-300 shadow-xl" src="<?php echo $user_picture; ?>" alt="user avatar" />
						<h3 class="text-xl font-semibold leading-normal my-2 text-gray-700">
							<?php echo $user_name; ?>
						</h3>
						<div class="flex items-center text-sm leading-normal mt-0 mb-2 text-gray-400 font-bold lowercase">
							<i class="fa-solid fa-envelope mr-2 text-lg text-gray-400"></i>
							<?php echo $user->email; ?>
						</div>
					</div>
					<div class="mt-10 pt-8 pb-0 border-t border-gray-200 text-center">
						<div class="flex flex-wrap justify-center">
							<div class="w-full lg:w-9/12 px-4">
								<p class="mb-4 text-base leading-relaxed text-gray-700">
									You are logged in using <span class="font-semibold"><?php echo ucwords($user->oauth_provider); ?></span>
								</p>
								<a href="<?php echo base_url(); ?>logout" class="font-normal text-orange-700 hover:opacity-50">
									Logout
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>