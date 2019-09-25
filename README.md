# Archive Filter

Enable using: `add_theme_support('sleek-archive-filter')`

Adds the ability to filter posts in an archive using GET params:

## `?sleek_filter_tax_{taxonomy_name}[]={term_id}`

Show only posts belonging to {term_id} in {taxonomy_name}.

## `?sleek_filter_meta_min_{meta_field_name}[]={value}`

Show only posts whos (numeric) {meta_field_name} is a minimum of {value}.

## `?sleek_filter_meta_max_{meta_field_name}[]={value}`

Show only posts whos (numeric) {meta_field_name} is a maximum of {value}.

## `?sleek_filter_meta_{meta_field_name}[]={value}`

Show only posts whos {meta_field_name} is exactly {value}.
