/**
 * Returns a Promise with the latest posts or an error on failure.
 *
 * @param   {Number} postsToShow       Number of posts to display.
 *
 * @returns {wp.api.collections.Posts} Returns a Promise with the latest posts.
 */
export function getGroups() {
  return fetch(ccbpress_core_blocks.api_url + "ccbpress/v1/admin/groups", {
    method: "POST",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      _wpnonce: ccbpress_core_blocks.api_nonce
    })
  });
}

export function getGroup(groupId) {
  if (groupId === null) {
    return;
  }
  return fetch(
    ccbpress_core_blocks.api_url + "ccbpress/v1/admin/group/" + groupId,
    {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        _wpnonce: ccbpress_core_blocks.api_nonce
      })
    }
  );
}

export function isFormActive(formId) {
  if (formId === null) {
    return false;
  }

  // let result = fetch(
  return fetch(
    ccbpress_core_blocks.api_url + "ccbpress/v1/admin/is-form-active/" + formId,
    {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        _wpnonce: ccbpress_core_blocks.api_nonce
      })
    }
  );
}
