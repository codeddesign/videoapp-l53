export default {
  title(route) {
    let title = route.name

    switch (route.name) {
      case 'dashboard':
        title = 'Dashboard'
        break
      case 'campaigns.create':
        title = 'Create Campaign'
        break
      case 'campaigns.listing':
        title = 'Your Campaigns'
        break
    }

    return title
  }
}
