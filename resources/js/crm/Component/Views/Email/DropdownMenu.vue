
<template>
    <div class="dropdown-menu dropdown-menu-right py-2 mt-1">
        <a class="dropdown-item" href="#">{{ $t( 'enabled' ) }}</a>
        <a class="dropdown-item" href="#">{{ $t( 'disabled' ) }}</a>
        <a class="dropdown-item" href="#">{{ $t( 'edit' ) }}</a>
        <a class="dropdown-item" href="#">{{ $t( 'Delete' ) }}</a>
        <a class="dropdown-item" href="#">{{ $t( 'settings' ) }}</a>
    </div>                                
</template>

<script>
export default {
    name: 'dropdown-menu',
}
</script>
 