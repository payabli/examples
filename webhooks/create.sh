curl -X POST https://api-sandbox.payabli.com/api/Notification \
     -H "requestToken: o.60Ain+qpKkFSyaHtpPwqgfgK8Tx9QWXosmVjVl9mZUo5Tpb3QVkCgagg2zIUIzbhrXR1ZVpwRpjcOC3IJnBF5twCDgA9Af1pQU32ZK40Fv0jhYQXUml4Qy5s29QhviShmqWfdU9VSUBUTvHbgNcWP6QxSJr1NzM6xD8Uqh5qIdv0O2lN+l9gVJxcnRWyTM8yGdvB6bMNro3nojX8t2I7u1nbxtdwan7xJeKQ5hbNH05Azx0QASqqCMftS0cTHNzFbYS1YiaERaO9dc0M2IJ+c1MUnkVD68MAFv+5mBq2RU5y/syFycVAsEDfMVZxpKJNVK2fRx78Bo+p4NwErCg8MxGfA7SlHaNRBTKFN/J3Se7ONrVPy6Y+DY8I2Znna2AaqEo1dPeQV035Fruw4tliXP5UxLAJlKU2gZ6VVkQICCufOFuSanD16XvUfyvrWXBXIO9i9EP15tOLm2F89zUohrlTv7Lb6FiDH84iHPYHhbAqp4QASPm/WzLRftwmgi19.ma7EL2FgOLGQih4hv4ipb1PwoG4Psch9C+Y8hSxL1po=" \
     -H "Content-Type: application/json" \
     -d '
{
  "content": {
    "eventType": "ApprovedPayment"
  },
  "frequency": "untilcancelled",
  "method": "web",
  "ownerId": "236",
  "ownerType": 0,
  "status": 1,
  "target": "https://webhook.site/11746825-d4e4-4ec7-a547-b0f53fb3911a"
}
'
