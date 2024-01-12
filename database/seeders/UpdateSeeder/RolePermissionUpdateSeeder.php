<?php
namespace Database\Seeders\UpdateSeeder;

use App\Models\Core\Auth\Permission;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\Type;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;
class RolePermissionUpdateSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        $this->seedNewPermissions();
        $this->newAgentPermissions();

        $this->enableForeignKeys();
    }

    public function seedNewPermissions() {
        $this->disableForeignKeys();
        $crmId = Type::findByAlias('crm')->id;

        $new_permissions = [
            //Invoice
            [
                'name' => 'view_invoice',
                'type_id' => $crmId,
                'group_name' => 'invoices'
            ],
            [
                'name' => 'create_invoice',
                'type_id' => $crmId,
                'group_name' => 'invoices'
            ],
            [
                'name' => 'update_invoice',
                'type_id' => $crmId,
                'group_name' => 'invoices'
            ],
            [
                'name' => 'delete_invoice',
                'type_id' => $crmId,
                'group_name' => 'invoices'
            ],
            [
                'name' => 'download_invoice',
                'type_id' => $crmId,
                'group_name' => 'invoices'
            ],
            [
                'name' => 'invoice_get_deal_contact_person',
                'type_id' => $crmId,
                'group_name' => 'invoices'
            ],
            [
                'name' => 'invoice_sending_attachment_mail',
                'type_id' => $crmId,
                'group_name' => 'invoices'
            ],
            //Deals
            [
                'name' => 'owner_deals',
                'type_id' => $crmId,
                'group_name' => 'deals'
            ],
            [
                'name' => 'details_deal',
                'type_id' => $crmId,
                'group_name' => 'deals'
            ],


            //Bulk action permission for person and organization module
            [
                'name' => 'bulk_attach_organizations_person',
                'type_id' => $crmId,
                'group_name' => 'bulk_actions'
            ],
            [
                'name' => 'bulk_update_lead_group_person',
                'type_id' => $crmId,
                'group_name' => 'bulk_actions'
            ],
            [
                'name' => 'bulk_update_owner_person',
                'type_id' => $crmId,
                'group_name' => 'bulk_actions'
            ],
            [
                'name' => 'bulk_attach_tags_person',
                'type_id' => $crmId,
                'group_name' => 'bulk_actions'
            ],
            [
                'name' => 'bulk_delete_person',
                'type_id' => $crmId,
                'group_name' => 'bulk_actions'
            ],
            [
                'name' => 'bulk_attach_persons_organization',
                'type_id' => $crmId,
                'group_name' => 'bulk_actions'
            ],
            [
                'name' => 'bulk_update_lead_group_organization',
                'type_id' => $crmId,
                'group_name' => 'bulk_actions'
            ],
            [
                'name' => 'bulk_update_owner_organization',
                'type_id' => $crmId,
                'group_name' => 'bulk_actions'
            ],
            [
                'name' => 'bulk_attach_tags_organization',
                'type_id' => $crmId,
                'group_name' => 'bulk_actions'
            ],
            [
                'name' => 'bulk_delete_organization',
                'type_id' => $crmId,
                'group_name' => 'bulk_actions'
            ]
        ];

        $this->enableForeignKeys();
        Permission::query()->insert($new_permissions);
    }

    public function newAgentPermissions() {
        $agentPermission = Permission::whereIn('group_name',
            [
                'bulk_actions'
            ])
            ->get();

        $agent = [];
        foreach ($agentPermission as $permission) {
            $agent[] = [
                'permission_id' => $permission->id
            ];
        }
        $agentRole = Role::where('name', 'Agent')->first();
        $agentRole->permissions()->sync($agent);
    }
}
