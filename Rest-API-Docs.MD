## REST API Documentation

Details about the REST API documentation of the plugin.

---

#### City dropdown list API
**URL** - http://localhost/wpex/wp-json/alert-real-estate/v1/cities/dropdown
**Method** - GET
**Response** -
```json
[
    {
        "id": "1",
        "name": "Akash",
        "email": "manirujjamanakash@gmail.com",
        "username": "admin"
    },
    {
        "id": "2",
        "name": "Polash Rana",
        "email": "polashrana@gmail.com",
        "username": "Polash"
    }
]
```

#### Alert Type List API
**URL** - http://localhost/wpex/wp-json/alert-real-estate/v1/alert-types
**Method** - GET
**Response** -
```json
[
    {
        "id": 1,
        "name": "Full time",
        "slug": "full-time",
        "description": "Full time alert",
        "created_at": "2022-07-31 12:36:20",
        "updated_at": "2022-07-31 12:36:20",
        "_links": {
            "self": [
                {
                    "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alert-types/1"
                }
            ],
            "collection": [
                {
                    "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alert-types"
                }
            ]
        }
    },
    {
        "id": 2,
        "name": "Part time",
        "slug": "part-time",
        "description": "Part time alert",
        "created_at": "2022-07-31 12:36:20",
        "updated_at": "2022-07-31 12:36:20",
        "_links": {
            "self": [
                {
                    "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alert-types/2"
                }
            ],
            "collection": [
                {
                    "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alert-types"
                }
            ]
        }
    },
    {
        "id": 3,
        "name": "Remote",
        "slug": "remote",
        "description": "Remote alert",
        "created_at": "2022-07-31 12:36:20",
        "updated_at": "2022-07-31 12:36:20",
        "_links": {
            "self": [
                {
                    "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alert-types/3"
                }
            ],
            "collection": [
                {
                    "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alert-types"
                }
            ]
        }
    }
]
```


#### Alert Lists API
**URL** - http://localhost/wpex/wp-json/alert-real-estate/v1/alerts
**Method** - `GET`
**Middleware** - WordPress user logged in.
**Parameters** -
```
page=1
per_page=10
search=first
orderby=id
order=asc
```
**Full Endpoint** with all of the supported params -
http://localhost/wpex/wp-json/alert-real-estate/v1/alerts?page=1&per_page=10&search=first

**API Response**
```json
[
    {
        "id": 1,
        "title": "First Alert",
        "slug": "first-alert",
        "alert_type": {
            "id": "1",
            "name": "Full time",
            "slug": "full-time"
        },
        "is_remote": false,
        "status": "published",
        "city": {
            "name": "Agami Soft Ltd.",
            "avatar_url": "http://1.gravatar.com/avatar/737340ad976d2c52de2a26589ff116d2?s=96&d=mm&r=g"
        },
        "description": "",
        "created_at": "2022-07-31 10:05:21",
        "updated_at": "2022-07-31 10:05:21",
        "_links": {
            "self": [
                {
                    "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alerts/1"
                }
            ],
            "collection": [
                {
                    "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alerts"
                }
            ]
        }
    }
]
```

#### Alert Detail API
**URL** - http://localhost/wpex/wp-json/alert-real-estate/v1/alerts/2
**Method** - `GET`
**Middleware** - WordPress user logged in.
**Response** -
```json
{
    "id": 2,
    "title": "Second Alert",
    "slug": "second-alert",
    "alert_type": {
        "id": "2",
        "name": "Part time",
        "slug": "part-time"
    },
    "is_remote": false,
    "status": "published",
    "city": {
        "name": "Maniruzzaman Akash",
        "avatar_url": "http://1.gravatar.com/avatar/4c0a55ab2542912358b53b7c99dc73c3?s=96&d=mm&r=g"
    },
    "description": "",
    "created_at": "2022-07-31 10:05:21",
    "updated_at": "2022-07-31 10:05:21",
    "_links": {
        "self": [
            {
                "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alerts/2"
            }
        ],
        "collection": [
            {
                "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alerts"
            }
        ]
    }
}
```

**Response when alert not found** -
```json
{
    "code": "bot_lite_rest_alert_not_found",
    "message": "Alert not found. May be alert has been deleted.",
    "data": {
        "status": 404
    }
}
```

#### Alert Create API
**URL** - http://localhost/wpex/wp-json/alert-real-estate/v1/alerts
**Method** - `POST`
**Middleware** - WordPress user logged in.
**Body** -
```json
{
    "title": "Simple Alert Post Awesome",
    "slug": "simple-alert-post-awesome",
    "description": "Simple alert post description",
    "city_id": 1,
    "alert_type_id": 2,
    "is_active": 1
}
```
**Response** -
```json
{
    "id": 49,
    "title": "Simple Alert Post Awesome",
    "slug": "simple-alert-post-awesome",
    "alert_type": {
        "id": "2",
        "name": "Part time",
        "slug": "part-time"
    },
    "is_remote": false,
    "status": "published",
    "city": {
        "name": "admin",
        "avatar_url": "http://1.gravatar.com/avatar/1c82531094a13dc2b339ab5decf5c446?s=96&d=mm&r=g"
    },
    "description": "Simple alert post description",
    "created_at": "2022-08-10 21:34:28",
    "updated_at": "2022-08-10 21:34:28",
    "_links": {
        "self": [
            {
                "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alerts/49"
            }
        ],
        "collection": [
            {
                "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alerts"
            }
        ]
    }
}
```
#### Alert Update API
**URL** - http://localhost/wpex/wp-json/alert-real-estate/v1/alerts/2
**Method** - `PUT`
**Middleware** - WordPress user logged in.
**Body** -
```json
{
    "title": "Simple Alert Post Awesome Updated",
    "slug": "simple-alert-post-awesome-updated",
    "description": "Simple alert post description",
    "city_id": 1,
    "alert_type_id": 2,
    "is_active": 1
}
```

**Response**
```json
{
    "id": 49,
    "title": "Simple Alert Post Awesome Updated",
    "slug": "simple-alert-post-awesome-updated",
    "alert_type": {
        "id": "2",
        "name": "Part time",
        "slug": "part-time"
    },
    "is_remote": false,
    "status": "published",
    "city": {
        "name": "admin",
        "avatar_url": "http://1.gravatar.com/avatar/1c82531094a13dc2b339ab5decf5c446?s=96&d=mm&r=g"
    },
    "description": "Simple alert post description",
    "created_at": "2022-08-10 21:36:09",
    "updated_at": "2022-08-10 21:36:09",
    "_links": {
        "self": [
            {
                "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alerts/49"
            }
        ],
        "collection": [
            {
                "href": "http://localhost/wpex/wp-json/alert-real-estate/v1/alerts"
            }
        ]
    }
}
```


#### Alerts Delete API
**URL** - http://localhost/wpex/wp-json/alert-real-estate/v1/alerts
**Method** - `DELETE`
**Middleware** - WordPress user logged in.
**Body** -
```json
{
    "ids": [1, 2]
}
```

**Response**
```json
{
    "message": "Alerts deleted successfully.",
    "total": 1
}
```