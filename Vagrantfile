Vagrant.configure("2") do |config|
  
	config.vm.box = "ubuntu/xenial64"

	config.vm.network "forwarded_port", guest: 80, host: 80
    config.vm.network "forwarded_port", guest: 5432, host: 5432
    config.vm.network "forwarded_port", guest: 443, host: 443

	config.vm.network "private_network", ip: "192.168.33.10"
	config.vm.synced_folder "website/", "/var/www"

	# Advanced settings
	config.vm.provider "virtualbox" do |vb|
		vb.memory = "1024"
	end
	
	config.vm.provision "shell", path: "./vagrant-provision.sh"
end
