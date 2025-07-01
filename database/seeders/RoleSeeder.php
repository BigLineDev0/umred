<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création des rôles
        $admin = Role::create(['name' => 'admin']);
        $chercheur = Role::create(['name' => 'chercheur']);
        $technicien = Role::create(['name' => 'technicien']);

        // Création des permissions
        $permissions = [
            // Gestion des utilisateurs
            'manage_users',
            'view_users',

            // Gestion des équipements
            'manage_equipments',
            'view_equipments',
            'reserve_equipment',

            // Gestion des réservations
            'manage_reservations',
            'view_reservations',
            'create_reservation',
            'cancel_reservation',

            // Gestion des maintenances
            'manage_maintenances',
            'view_maintenances',
            'create_maintenance',

            // Gestion des laboratoires
            'manage_laboratories',
            'view_laboratories',

            // Notifications
            'send_notifications',
            'view_notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Attribution des permissions aux rôles
        $admin->givePermissionTo([
            'manage_users', 'view_users',
            'manage_equipments', 'view_equipments',
            'manage_reservations', 'view_reservations',
            'manage_maintenances', 'view_maintenances',
            'manage_laboratories', 'view_laboratories',
            'send_notifications', 'view_notifications',
        ]);

        $chercheur->givePermissionTo([
            'view_equipments', 'reserve_equipment',
            'view_reservations', 'create_reservation', 'cancel_reservation',
            'view_laboratories',
            'view_notifications',
        ]);

        $technicien->givePermissionTo([
            'view_equipments', 'manage_equipments',
            'view_reservations', 'manage_reservations',
            'view_maintenances', 'create_maintenance', 'manage_maintenances',
            'view_laboratories',
            'view_notifications',
        ]);
    }
}
