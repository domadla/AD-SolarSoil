# Labels
| Label    | When to use                                                                                                   |
| -------- | ------------------------------------------------------------------------------------------------------------- |
| feat     | When adding new file or new code                                                                              |
| refactor | Delete a file or code and/or alter a code                                                                     |
| fix      | There is bug found and manage to be solved                                                                    |
| docs     | anything involving documentation; readme.md, markdown files (.md), docs files(.pdf, .docs, etc.) and comments |

# Format

```md
<label>(<affected file>): short description

detailed description(Tips: make it dot format)
```

Example:
```md
feat(landing page): added feature section

- added carousel
- added call to action
- formated responsive design
```

```md
fix(userData.handler.php): Fixed issue in pushing data to database

- fixed data structure and pushing update data from `put`
```
```md
docs(userData.handler.php): Added Comment notes for description of function and how to use it
```

