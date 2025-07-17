<div class="panel">
  <h3>{l s='Product Reference API Configuration' mod='referenceapi'}</h3>
  <p>{l s='Configure the API key to secure your API endpoint.' mod='referenceapi'}</p>
  {if isset($smarty.get.confirmation)}
    <div class="alert alert-success">{l s='Settings updated successfully.' mod='referenceapi'}</div>
  {/if}
  {if isset($smarty.get.error)}
    <div class="alert alert-danger">{l s='Invalid API key.' mod='referenceapi'}</div>
  {/if}
</div>