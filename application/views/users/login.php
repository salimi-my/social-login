<div class="bg-gradient-to-br from-sky-50 to-gray-200 h-screen flex justify-center items-center">
	<div class="relative container m-auto px-6 text-gray-500 md:px-12 xl:px-40">
		<div class="m-auto md:w-8/12 lg:w-6/12 xl:w-6/12">
			<div class="rounded-xl bg-white shadow-xl">
				<div class="p-6 sm:p-16">
					<div class="space-y-4">
						<img src="<?php echo base_url(); ?>assets/images/social-login.png" class="h-20" width="80" height="80" alt="social login">
						<h2 class="mb-8 text-2xl text-cyan-900 font-bold">Codeigniter social <br> media login.</h2>
					</div>
					<div class="mt-16 grid space-y-4">
						<button class="group h-12 px-6 border-2 border-gray-300 rounded-full transition duration-300 hover:border-orange-600 focus:bg-blue-50 active:bg-blue-100">
							<div class="relative flex items-center space-x-4 justify-center">
								<img src="<?php echo base_url(); ?>assets/images/google-icon.svg" class="absolute left-0 w-5" width="20" height="20" alt="google logo">
								<a href="<?php echo $gl_login_url; ?>" class="block w-max font-semibold tracking-wide text-gray-700 text-sm transition duration-300 group-hover:text-orange-600 sm:text-base">
									Login with Google
								</a>
							</div>
						</button>
						<button class="group h-12 px-6 border-2 border-gray-300 rounded-full transition duration-300 hover:border-orange-600 focus:bg-blue-50 active:bg-blue-100">
							<div class="relative flex items-center space-x-4 justify-center">
								<img src="<?php echo base_url(); ?>assets/images/twitter-icon.svg" class="absolute left-0 w-5" width="20" height="20" alt="twitter logo">
								<a href="<?php echo $tw_login_url; ?>" class="block w-max font-semibold tracking-wide text-gray-700 text-sm transition duration-300 group-hover:text-orange-600 sm:text-base">
									Login with Twitter
								</a>
							</div>
						</button>
						<button class="group h-12 px-6 border-2 border-gray-300 rounded-full transition duration-300 hover:border-orange-600 focus:bg-blue-50 active:bg-blue-100">
							<div class="relative flex items-center space-x-4 justify-center">
								<img src="<?php echo base_url(); ?>assets/images/facebook-icon.svg" class="absolute left-0 w-5" width="20" height="20" alt="facebook logo">
								<a href="<?php echo $fb_login_url; ?>" class="block w-max font-semibold tracking-wide text-gray-700 text-sm transition duration-300 group-hover:text-orange-600 sm:text-base">
									Login with Facebook
								</a>
							</div>
						</button>
					</div>

					<div class="mt-32 space-y-4 text-gray-600 text-center sm:-mb-8">
						<p class="text-xs">A simple authentication app using social media account.</p>
						<p class="text-xs">By proceeding, you confirm that you have read our <a href="<?php echo base_url() . 'privacy-policy'; ?>" class="underline">Privacy Policy</a> and agree that your use of the site and your reliance on any information on the site is solely at your own risk</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>