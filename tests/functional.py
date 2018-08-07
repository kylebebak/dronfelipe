import unittest
import sys

from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait


class Functional(unittest.TestCase):

    @classmethod
    def setUpClass(self):
        self.FAST_TIMEOUT = 5
        self.TIMEOUT = 30
        self.base_site = base_site
        self.random_segment = 'bvGnGqGr4nBl'
        self.driver = webdriver.Firefox()
        self.driver.set_page_load_timeout(self.TIMEOUT)

    @classmethod
    def tearDownClass(self):
        self.driver.close()

    def wait_for_ajax(self, timeout=10, driver=None):
        """Driver waits for AJAX request to finish, unless timeout occurs
        first, in which case an exception is raised."""
        driver = driver if driver else self.driver
        wait = WebDriverWait(driver, timeout)
        wait.until(lambda driver: driver.execute_script(
            "return jQuery.active == 0"))

    def test_home(self):
        """Go to home page, click on dropdown, click on link to
        about page, go back to home page."""
        driver = self.driver
        driver.get(self.base_site)
        self.assertIn("dronfelipe", driver.title)

        driver.find_element_by_css_selector('a#home').click()
        driver.find_elements_by_css_selector('ul.dropdown-menu a')[-1].click()
        assert "Kyle Bebak" in driver.page_source

        driver.find_element_by_css_selector('a#home').click()
        self.assertIn("dronfelipe", driver.title)

    def test_non_existent_page(self):
        """Check that non-existent pages redirect to 404 page."""
        driver = self.driver
        driver.get('{}/{}'.format(self.base_site, self.random_segment))
        assert "This page doesn't exist..." in driver.page_source

    def test_tortas(self):
        """Go to page, wait for menu to load, and check that the name of
        the puesto is correctly formed."""
        driver = self.driver
        driver.get('{}/tortas'.format(self.base_site))
        self.wait_for_ajax()
        menu_header = driver.find_element_by_css_selector('h2.menu-name')
        self.assertIn("torta", menu_header.text)

    def test_images(self):
        """Go to images, get first image filename, go to URL that
        should contain high-resolution version of this image
        and check that it's there."""
        driver = self.driver
        driver.get('{}/images'.format(self.base_site))
        image = driver.find_elements_by_css_selector('section#images img')[0]
        driver.get('{}/images/img/{}'.format(
            self.base_site, image.get_attribute('data-name')))
        driver.find_element_by_css_selector('img')

    def test_location_history(self):
        """Go to location search box, push down arrow, push enter,
        check that info window has been inserted into DOM."""
        driver = self.driver
        driver.get('{}/location_history'.format(self.base_site))
        self.wait_for_ajax()
        driver.find_element_by_css_selector('a.select2-choice').click()
        search = driver.find_element_by_css_selector('div.select2-search input')
        search.send_keys(Keys.DOWN)
        search.send_keys(Keys.RETURN)
        driver.find_element_by_css_selector('p#header')


if __name__ == "__main__":
    """
    Args can be passed to the TestCase by (ab)using global arguments.
    The base site must be the last command line argument passed to this
    script, and it must be have the form 's=<base_site>'.
    """
    global base_site
    base_site = "http://localhost"
    site = sys.argv[-1].split('s=')
    if len(site) > 1:
        base_site = site[1]
        sys.argv.pop()
    unittest.main()
