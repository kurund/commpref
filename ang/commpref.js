(function (angular, $, _) {
  // Declare a list of dependencies.
  angular.module("commpref", CRM.angRequires("commpref"));

  angular.module("commpref").component("contactName", {
    templateUrl: "~/commpref/contactName.html",
    controller: function ($scope, crmApi4) {
      var ctrl = this;

      this.$onInit = function () {
        // set contact name
        this.getContactName();

        // check opt out checkbox
        this.uncheckGroups();
      };

      // get contact name and set it to scope
      this.getContactName = function () {
        crmApi4("Contact", "get", {
          select: ["first_name", "last_name", "prefix_id:label"],
          where: [["id", "=", "user_contact_id"]],
        }).then(function (contacts) {
          var contactName = contacts[0]["first_name"] + " " + contacts[0]["last_name"];
          if (contacts[0]["prefix_id:label"]) {
            contactName = contacts[0]["prefix_id:label"] + " " + contactName;
          }
          ctrl.contactName = contactName;
        });
      };

      this.uncheckGroups = function () {
        // TODOS: Implement checkbox logic
      };
    },
  });
})(angular, CRM.$, CRM._);
