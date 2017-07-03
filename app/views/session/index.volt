{{ form("session/login", "method": "post", "class": "form-search") }}
  <h2>Login</h2>
  <div>
    {{ form.label("email") }}
    {{ form.render("email") }}
  </div>

  <div>
    {{ form.label("password") }}
    {{ form.render("password") }}
  </div>

  <div>
    {{ submit_button("Save", "class": "btn btn-big btn-success") }}
  </div>
{{ endForm() }}
